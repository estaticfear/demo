<?php

namespace Cmat\ReligiousMerit\Http\Controllers;

use Cmat\ACL\Models\User;
use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\ReligiousMerit\Http\Requests\ReligiousMeritProjectCategoryRequest;
use Cmat\ReligiousMerit\Repositories\Interfaces\ReligiousMeritProjectCategoryInterface;
use Cmat\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Cmat\ReligiousMerit\Tables\ReligiousMeritProjectCategoryTable;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\ReligiousMerit\Forms\ReligiousMeritProjectCategoryForm;
use Cmat\Base\Forms\FormBuilder;
use Illuminate\Support\Facades\Auth;

class ReligiousMeritProjectCategoryController extends BaseController
{
    protected ReligiousMeritProjectCategoryInterface $repository;

    public function __construct(ReligiousMeritProjectCategoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(ReligiousMeritProjectCategoryTable $table)
    {
        page_title()->setTitle(trans('plugins/religious-merit::religious-merit.categories'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/religious-merit::religious-merit.create'));

        return $formBuilder->create(ReligiousMeritProjectCategoryForm::class)->renderForm();
    }

    public function store(ReligiousMeritProjectCategoryRequest $request, BaseHttpResponse $response)
    {
        $category = $this->repository->createOrUpdate(
            array_merge($request->input(), [
                'author_id' => Auth::id(),
                'author_type' => User::class,
            ])
        );

        event(new CreatedContentEvent(RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $request, $category));

        return $response
            ->setPreviousUrl(route('religious-merit-project-category.index'))
            ->setNextUrl(route('religious-merit-project-category.edit', $category->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $category = $this->repository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $category));

        page_title()->setTitle(trans('plugins/religious-merit::religious-merit.edit') . ' "' . $category->name . '"');

        return $formBuilder->create(ReligiousMeritProjectCategoryForm::class, ['model' => $category])->renderForm();
    }

    public function update(int|string $id, ReligiousMeritProjectCategoryRequest $request, BaseHttpResponse $response)
    {
        $category = $this->repository->findOrFail($id);

        $category->fill($request->input());

        $category = $this->repository->createOrUpdate($category);

        event(new UpdatedContentEvent(RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $request, $category));

        return $response
            ->setPreviousUrl(route('religious-merit-project-category.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $category = $this->repository->findOrFail($id);

            $this->repository->delete($category);

            event(new DeletedContentEvent(RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $request, $category));

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
            $category = $this->repository->findOrFail($id);
            $this->repository->delete($category);
            event(new DeletedContentEvent(RELIGIOUS_MERIT_MODULE_SCREEN_NAME, $request, $category));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
