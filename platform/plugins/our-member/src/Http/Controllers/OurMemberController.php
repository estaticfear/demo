<?php

namespace Cmat\OurMember\Http\Controllers;

use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\OurMember\Http\Requests\OurMemberRequest;
use Cmat\OurMember\Repositories\Interfaces\OurMemberInterface;
use Cmat\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Cmat\OurMember\Tables\OurMemberTable;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\OurMember\Forms\OurMemberForm;
use Cmat\Base\Forms\FormBuilder;

class OurMemberController extends BaseController
{
    protected OurMemberInterface $ourMemberRepository;

    public function __construct(OurMemberInterface $ourMemberRepository)
    {
        $this->ourMemberRepository = $ourMemberRepository;
    }

    public function index(OurMemberTable $table)
    {
        page_title()->setTitle(trans('plugins/our-member::our-member.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/our-member::our-member.create'));

        return $formBuilder->create(OurMemberForm::class)->renderForm();
    }

    public function store(OurMemberRequest $request, BaseHttpResponse $response)
    {
        $ourMember = $this->ourMemberRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(OUR_MEMBER_MODULE_SCREEN_NAME, $request, $ourMember));

        return $response
            ->setPreviousUrl(route('our-member.index'))
            ->setNextUrl(route('our-member.edit', $ourMember->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $ourMember = $this->ourMemberRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $ourMember));

        page_title()->setTitle(trans('plugins/our-member::our-member.edit') . ' "' . $ourMember->name . '"');

        return $formBuilder->create(OurMemberForm::class, ['model' => $ourMember])->renderForm();
    }

    public function update(int|string $id, OurMemberRequest $request, BaseHttpResponse $response)
    {
        $ourMember = $this->ourMemberRepository->findOrFail($id);

        $ourMember->fill($request->input());

        $ourMember = $this->ourMemberRepository->createOrUpdate($ourMember);

        event(new UpdatedContentEvent(OUR_MEMBER_MODULE_SCREEN_NAME, $request, $ourMember));

        return $response
            ->setPreviousUrl(route('our-member.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $ourMember = $this->ourMemberRepository->findOrFail($id);

            $this->ourMemberRepository->delete($ourMember);

            event(new DeletedContentEvent(OUR_MEMBER_MODULE_SCREEN_NAME, $request, $ourMember));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
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
            $ourMember = $this->ourMemberRepository->findOrFail($id);
            $this->ourMemberRepository->delete($ourMember);
            event(new DeletedContentEvent(OUR_MEMBER_MODULE_SCREEN_NAME, $request, $ourMember));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
