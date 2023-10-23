<?php

namespace Cmat\Member\Http\Controllers;

use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Base\Traits\HasDeleteManyItemsTrait;
use Cmat\Media\Repositories\Interfaces\MediaFileInterface;
use Cmat\Member\Forms\MemberForm;
use Cmat\Member\Http\Requests\MemberCreateRequest;
use Cmat\Member\Http\Requests\MemberEditRequest;
use Cmat\Member\Repositories\Interfaces\MemberInterface;
use Cmat\Member\Tables\MemberTable;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class MemberController extends BaseController
{
    use HasDeleteManyItemsTrait;

    public function __construct(protected MemberInterface $memberRepository)
    {
    }

    public function index(MemberTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/member::member.menu_name'));

        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/member::member.create'));

        return $formBuilder
            ->create(MemberForm::class)
            ->remove('is_change_password')
            ->renderForm();
    }

    public function store(MemberCreateRequest $request, BaseHttpResponse $response)
    {
        $member = $this->memberRepository->getModel();
        $member->fill($request->input());
        $member->confirmed_at = Carbon::now();
        $member->password = bcrypt($request->input('password'));
        $member->dob = Carbon::parse($request->input('dob'))->toDateString();

        if ($request->input('avatar_image')) {
            $image = app(MediaFileInterface::class)->getFirstBy(['url' => $request->input('avatar_image')]);
            if ($image) {
                $member->avatar_id = $image->id;
            }
        }

        $member = $this->memberRepository->createOrUpdate($member);

        event(new CreatedContentEvent(MEMBER_MODULE_SCREEN_NAME, $request, $member));

        return $response
            ->setPreviousUrl(route('member.index'))
            ->setNextUrl(route('member.edit', $member->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $member = $this->memberRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $member));

        page_title()->setTitle(trans('plugins/member::member.edit'));

        $member->password = null;

        return $formBuilder
            ->create(MemberForm::class, ['model' => $member])
            ->renderForm();
    }

    public function update(int|string $id, MemberEditRequest $request, BaseHttpResponse $response)
    {
        $member = $this->memberRepository->findOrFail($id);

        $member->fill($request->except('password'));

        if ($request->input('is_change_password') == 1) {
            $member->password = bcrypt($request->input('password'));
        }

        $member->dob = Carbon::parse($request->input('dob'))->toDateString();

        if ($request->input('avatar_image')) {
            $image = app(MediaFileInterface::class)->getFirstBy(['url' => $request->input('avatar_image')]);
            if ($image) {
                $member->avatar_id = $image->id;
            }
        }

        $member = $this->memberRepository->createOrUpdate($member);

        event(new UpdatedContentEvent(MEMBER_MODULE_SCREEN_NAME, $request, $member));

        return $response
            ->setPreviousUrl(route('member.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $member = $this->memberRepository->findOrFail($id);
            $this->memberRepository->delete($member);
            event(new DeletedContentEvent(MEMBER_MODULE_SCREEN_NAME, $request, $member));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->memberRepository, MEMBER_MODULE_SCREEN_NAME);
    }
}
