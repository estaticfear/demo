<?php

namespace Cmat\ACL\Http\Controllers;

use Assets;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Media\Services\ThumbnailService;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Cmat\ACL\Forms\PasswordForm;
use Cmat\ACL\Forms\ProfileForm;
use Cmat\ACL\Forms\UserForm;
use Cmat\ACL\Tables\UserTable;
use Cmat\ACL\Http\Requests\CreateUserRequest;
use Cmat\ACL\Http\Requests\UpdatePasswordRequest;
use Cmat\ACL\Http\Requests\UpdateProfileRequest;
use Cmat\ACL\Models\UserMeta;
use Cmat\ACL\Repositories\Interfaces\RoleInterface;
use Cmat\ACL\Repositories\Interfaces\UserInterface;
use Cmat\ACL\Services\ChangePasswordService;
use Cmat\ACL\Services\CreateUserService;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Media\Repositories\Interfaces\MediaFileInterface;
use Cmat\ACL\Http\Requests\AvatarRequest;
use Exception;
use Illuminate\Http\Request;
use RvMedia;

class UserController extends BaseController
{
    public function __construct(
        protected UserInterface $userRepository,
        protected RoleInterface $roleRepository,
        protected MediaFileInterface $fileRepository
    ) {
    }

    public function index(UserTable $dataTable)
    {
        page_title()->setTitle(trans('core/acl::users.users'));

        Assets::addScripts(['bootstrap-editable', 'jquery-ui'])
            ->addStyles(['bootstrap-editable']);

        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('core/acl::users.create_new_user'));

        return $formBuilder->create(UserForm::class)->renderForm();
    }

    public function store(CreateUserRequest $request, CreateUserService $service, BaseHttpResponse $response)
    {
        $user = $service->execute($request);

        event(new CreatedContentEvent(USER_MODULE_SCREEN_NAME, $request, $user));

        return $response
            ->setPreviousUrl(route('users.index'))
            ->setNextUrl(route('users.profile.view', $user->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->getKey() == $id) {
            return $response
                ->setError()
                ->setMessage(trans('core/acl::users.delete_user_logged_in'));
        }

        try {
            $user = $this->userRepository->findOrFail($id);

            if (! $request->user()->isSuperUser() && $user->isSuperUser()) {
                return $response
                    ->setError()
                    ->setMessage(trans('core/acl::users.cannot_delete_super_user'));
            }

            $this->userRepository->delete($user);
            event(new DeletedContentEvent(USER_MODULE_SCREEN_NAME, $request, $user));

            return $response->setMessage(trans('core/acl::users.deleted'));
        } catch (Exception) {
            return $response
                ->setError()
                ->setMessage(trans('core/acl::users.cannot_delete'));
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            if ($request->user()->getKey() == $id) {
                return $response
                    ->setError()
                    ->setMessage(trans('core/acl::users.delete_user_logged_in'));
            }

            try {
                $user = $this->userRepository->findOrFail($id);
                if (! $request->user()->isSuperUser() && $user->isSuperUser()) {
                    continue;
                }
                $this->userRepository->delete($user);
                event(new DeletedContentEvent(USER_MODULE_SCREEN_NAME, $request, $user));
            } catch (Exception $exception) {
                return $response
                    ->setError()
                    ->setMessage($exception->getMessage());
            }
        }

        return $response->setMessage(trans('core/acl::users.deleted'));
    }

    public function getUserProfile(int|string $id, Request $request, FormBuilder $formBuilder)
    {
        Assets::addScripts(['bootstrap-pwstrength', 'cropper'])
            ->addScriptsDirectly('vendor/core/core/acl/js/profile.js');

        $user = $this->userRepository->findOrFail($id);

        page_title()->setTitle(trans(':name', ['name' => $user->name]));

        $form = $formBuilder
            ->create(ProfileForm::class, ['model' => $user])
            ->setUrl(route('users.update-profile', $user->id));

        $passwordForm = $formBuilder
            ->create(PasswordForm::class)
            ->setUrl(route('users.change-password', $user->id));

        $currentUser = $request->user();

        $canChangeProfile = $currentUser->hasPermission('users.update-profile') || $currentUser->getKey() == $id || $currentUser->isSuperUser();

        if (! $canChangeProfile) {
            $form->disableFields();
            $form->removeActionButtons();
            $form->setActionButtons(' ');
            $passwordForm->disableFields();
            $passwordForm->removeActionButtons();
            $passwordForm->setActionButtons(' ');
        }

        if ($currentUser->isSuperUser()) {
            $passwordForm->remove('old_password');
        }

        $form = $form->renderForm();
        $passwordForm = $passwordForm->renderForm();

        return view('core/acl::users.profile.base', compact('user', 'form', 'passwordForm', 'canChangeProfile'));
    }

    public function postUpdateProfile(int|string $id, UpdateProfileRequest $request, BaseHttpResponse $response)
    {
        $user = $this->userRepository->findOrFail($id);

        $currentUser = $request->user();

        $hasRightToUpdate = ($currentUser->hasPermission('users.update-profile') && $currentUser->getKey() === $user->id) ||
            $currentUser->isSuperUser();

        if (! $hasRightToUpdate) {
            return $response
                ->setNextUrl(route('users.profile.view', $user->id))
                ->setError()
                ->setMessage(trans('core/acl::permissions.access_denied_message'));
        }

        if ($user->email !== $request->input('email')) {
            $users = $this->userRepository
                ->getModel()
                ->where('email', $request->input('email'))
                ->where('id', '<>', $user->id)
                ->exists();

            if ($users) {
                return $response
                    ->setError()
                    ->setMessage(trans('core/acl::users.email_exist'))
                    ->withInput();
            }
        }

        if ($user->username !== $request->input('username')) {
            $users = $this->userRepository
                ->getModel()
                ->where('username', $request->input('username'))
                ->where('id', '<>', $user->id)
                ->exists();

            if ($users) {
                return $response
                    ->setError()
                    ->setMessage(trans('core/acl::users.username_exist'))
                    ->withInput();
            }
        }

        $user->fill($request->input());
        $this->userRepository->createOrUpdate($user);
        do_action(USER_ACTION_AFTER_UPDATE_PROFILE, USER_MODULE_SCREEN_NAME, $request, $user);

        event(new UpdatedContentEvent(USER_MODULE_SCREEN_NAME, $request, $user));

        return $response->setMessage(trans('core/acl::users.update_profile_success'));
    }

    public function postChangePassword(
        int|string $id,
        UpdatePasswordRequest $request,
        ChangePasswordService $service,
        BaseHttpResponse $response
    ) {
        $user = $this->userRepository->findOrFail($id);

        $currentUser = $request->user();

        $hasRightToUpdate = ($currentUser->hasPermission('users.update-profile') && $currentUser->getKey() === $user->id) ||
            $currentUser->isSuperUser();

        if (! $hasRightToUpdate) {
            return $response
                ->setNextUrl(route('users.profile.view', $user->id))
                ->setError()
                ->setMessage(trans('core/acl::permissions.access_denied_message'));
        }

        $request->merge(['id' => $id]);
        $result = $service->execute($request);

        if ($result instanceof Exception) {
            return $response
                ->setError()
                ->setMessage($result->getMessage());
        }

        return $response->setMessage(trans('core/acl::users.password_update_success'));
    }

    public function postAvatar(int|string $id, AvatarRequest $request, ThumbnailService $thumbnailService, BaseHttpResponse $response)
    {
        $user = $this->userRepository->findOrFail($id);

        $currentUser = $request->user();

        $hasRightToUpdate = ($currentUser->hasPermission('users.update-profile') && $currentUser->getKey() === $user->id) ||
            $currentUser->isSuperUser();

        if (! $hasRightToUpdate) {
            return $response
                ->setNextUrl(route('users.profile.view', $user->id))
                ->setError()
                ->setMessage(trans('core/acl::permissions.access_denied_message'));
        }

        try {
            $result = RvMedia::handleUpload($request->file('avatar_file'), 0, 'users');

            if ($result['error']) {
                return $response->setError()->setMessage($result['message']);
            }

            $avatarData = json_decode($request->input('avatar_data'));

            $file = $result['data'];

            $thumbnailService
                ->setImage(RvMedia::getRealPath($file->url))
                ->setSize((int)$avatarData->width ?: 150, (int)$avatarData->height ?: 150)
                ->setCoordinates((int)$avatarData->x, (int)$avatarData->y)
                ->setDestinationPath(File::dirname($file->url))
                ->setFileName(File::name($file->url) . '.' . File::extension($file->url))
                ->save('crop');

            $this->fileRepository->forceDelete(['id' => $user->avatar_id]);

            $user->avatar_id = $file->id;

            $this->userRepository->createOrUpdate($user);

            return $response
                ->setMessage(trans('core/acl::users.update_avatar_success'))
                ->setData(['url' => RvMedia::url($file->url)]);
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function getTheme(string $theme)
    {
        if (Auth::check() && ! app()->environment('demo')) {
            UserMeta::setMeta('admin-theme', $theme);
        }

        session()->put('admin-theme', $theme);

        try {
            return redirect()->back();
        } catch (Exception) {
            return redirect()->route('access.login');
        }
    }

    public function makeSuper(int|string $id, BaseHttpResponse $response)
    {
        try {
            $user = $this->userRepository->findOrFail($id);

            $user->updatePermission(ACL_ROLE_SUPER_USER, true);
            $user->updatePermission(ACL_ROLE_MANAGE_SUPERS, true);
            $user->super_user = 1;
            $user->manage_supers = 1;
            $this->userRepository->createOrUpdate($user);

            return $response
                ->setNextUrl(route('users.index'))
                ->setMessage(trans('core/base::system.supper_granted'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setNextUrl(route('users.index'))
                ->setMessage($exception->getMessage());
        }
    }

    public function removeSuper(int|string $id, Request $request, BaseHttpResponse $response)
    {
        if ($request->user()->getKey() == $id) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::system.cannot_revoke_yourself'));
        }

        $user = $this->userRepository->findOrFail($id);

        $user->updatePermission(ACL_ROLE_SUPER_USER, false);
        $user->updatePermission(ACL_ROLE_MANAGE_SUPERS, false);
        $user->super_user = 0;
        $user->manage_supers = 0;
        $this->userRepository->createOrUpdate($user);

        return $response
            ->setNextUrl(route('users.index'))
            ->setMessage(trans('core/base::system.supper_revoked'));
    }

    public function toggleSidebarMenu(Request $request, BaseHttpResponse $response)
    {
        $status = $request->input('status') == 'true';

        session()->put('sidebar-menu-toggle', $status ? Carbon::now() : '');

        return $response->setMessage($status);
    }
}
