<?php

namespace Cmat\Member\Http\Controllers;

use Assets;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Media\Chunks\Exceptions\UploadMissingFileException;
use Cmat\Media\Chunks\Handler\DropZoneUploadHandler;
use Cmat\Media\Chunks\Receiver\FileReceiver;
use Cmat\Media\Repositories\Interfaces\MediaFileInterface;
use Cmat\Media\Services\ThumbnailService;
use Cmat\Member\Http\Requests\AvatarRequest;
use Cmat\Member\Http\Requests\SettingRequest;
use Cmat\Member\Http\Requests\UpdatePasswordRequest;
use Cmat\Member\Http\Resources\ActivityLogResource;
use Cmat\Member\Repositories\Interfaces\MemberActivityLogInterface;
use Cmat\Member\Repositories\Interfaces\MemberInterface;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use RvMedia;
use SeoHelper;
use Theme;

class PublicController extends Controller
{
    public function __construct(
        Repository $config,
        protected MemberInterface $memberRepository,
        protected MemberActivityLogInterface $activityLogRepository,
        protected MediaFileInterface $fileRepository
    ) {
        Assets::setConfig($config->get('plugins.member.assets', []));
    }

    public function getDashboard()
    {
        $user = auth('member')->user();

        SeoHelper::setTitle($user->name);

        Assets::addScriptsDirectly(['vendor/core/plugins/member/libraries/cropper.js'])
            ->usingVueJS();

            $data = [
                'view' => 'member-dashboard',
                'default_view' => 'plugins/member::dashboard.index',
                'data' => [
                    'user' => $user
                ]
            ];

        // return view('plugins/member::dashboard.index', compact('user'));
        return Theme::layout('no-sidebar')->scope($data['view'], $data['data'], $data['default_view'])->render();
    }

    public function getSettings()
    {
        SeoHelper::setTitle(__('Account settings'));

        $user = auth('member')->user();

        Assets::addScriptsDirectly(['vendor/core/plugins/member/libraries/cropper.js']);

        $data = [
            'view' => 'member-settings',
            'default_view' => 'plugins/member::settings.index',
            'data' => [
                'user' => $user
            ]
        ];

        // return view('plugins/member::settings.index', compact('user'));
        return Theme::layout('no-sidebar-no-breadcrumbs')->scope($data['view'], $data['data'], $data['default_view'])->render();
    }

    public function postSettings(SettingRequest $request, BaseHttpResponse $response)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');

        if ($year && $month && $day) {
            $request->merge(['dob' => implode('-', [$year, $month, $day])]);

            $validator = Validator::make($request->input(), [
                'dob' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return redirect()->route('public.member.settings');
            }
        }

        $this->memberRepository->createOrUpdate($request->except('email'), ['id' => auth('member')->id()]);

        $this->activityLogRepository->createOrUpdate(['action' => 'update_setting']);

        return $response
            ->setNextUrl(route('public.member.settings'));
            // ->setMessage(__('Update profile successfully!'));
    }

    public function getSecurity()
    {
        SeoHelper::setTitle(__('Security'));

        return view('plugins/member::settings.security');
    }

    public function postSecurity(UpdatePasswordRequest $request, BaseHttpResponse $response)
    {
        $this->memberRepository->update(['id' => auth('member')->id()], [
            'password' => bcrypt($request->input('password')),
        ]);

        $this->activityLogRepository->createOrUpdate(['action' => 'update_security']);

        return $response->setMessage(trans('plugins/member::dashboard.password_update_success'));
    }

    public function postAvatar(AvatarRequest $request, ThumbnailService $thumbnailService, BaseHttpResponse $response)
    {
        try {
            $account = auth('member')->user();

            $result = RvMedia::handleUpload($request->file('avatar_file'), 0, $account->upload_folder);

            if ($result['error']) {
                return $response->setError()->setMessage($result['message']);
            }

            $avatarData = json_decode($request->input('avatar_data'));

            $file = $result['data'];

            $thumbnailService
                ->setImage(RvMedia::getRealPath($file->url))
                ->setSize((int)$avatarData->width, (int)$avatarData->height)
                ->setCoordinates((int)$avatarData->x, (int)$avatarData->y)
                ->setDestinationPath(File::dirname($file->url))
                ->setFileName(File::name($file->url) . '.' . File::extension($file->url))
                ->save('crop');

            $this->fileRepository->forceDelete(['id' => $account->avatar_id]);

            $account->avatar_id = $file->id;

            $this->memberRepository->createOrUpdate($account);

            $this->activityLogRepository->createOrUpdate([
                'action' => 'changed_avatar',
            ]);

            return $response
                ->setMessage(trans('plugins/member::dashboard.update_avatar_success'))
                ->setData(['url' => RvMedia::url($file->url)]);
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function getActivityLogs(BaseHttpResponse $response)
    {
        $activities = $this->activityLogRepository->getAllLogs(auth('member')->id());

        return $response->setData(ActivityLogResource::collection($activities))->toApiResponse();
    }

    public function postUpload(Request $request, BaseHttpResponse $response)
    {
        $account = auth('member')->user();

        if (! RvMedia::isChunkUploadEnabled()) {
            $validator = Validator::make($request->all(), [
                'file.0' => RvMedia::imageValidationRule(),
            ]);

            if ($validator->fails()) {
                return $response->setError()->setMessage($validator->getMessageBag()->first());
            }

            $result = RvMedia::handleUpload(Arr::first($request->file('file')), 0, $account->upload_folder);

            if ($result['error']) {
                return $response->setError()->setMessage($result['message']);
            }

            return $response->setData($result['data']);
        }

        try {
            // Create the file receiver
            $receiver = new FileReceiver('file', $request, DropZoneUploadHandler::class);
            // Check if the upload is success, throw exception or return response you need
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException();
            }
            // Receive the file
            $save = $receiver->receive();
            // Check if the upload has finished (in chunk mode it will send smaller files)
            if ($save->isFinished()) {
                $result = RvMedia::handleUpload($save->getFile(), 0, $account->upload_folder);

                if (! $result['error']) {
                    return $response->setData($result['data']);
                }

                return $response->setError()->setMessage($result['message']);
            }
            // We are in chunk mode, lets send the current progress
            $handler = $save->handler();

            return response()->json([
                'done' => $handler->getPercentageDone(),
                'status' => true,
            ]);
        } catch (Exception $exception) {
            return $response->setError()->setMessage($exception->getMessage());
        }
    }

    public function postUploadFromEditor(Request $request)
    {
        $account = auth('member')->user();

        return RvMedia::uploadFromEditor($request, 0, $account->upload_folder);
    }
}
