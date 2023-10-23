<?php

namespace Cmat\Blog\Http\Controllers;

use Cmat\ACL\Models\User;
use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\DeletedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Base\Http\Controllers\BaseController;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Base\Traits\HasDeleteManyItemsTrait;
use Cmat\Blog\Forms\PostForm;
use Cmat\Blog\Http\Requests\PostRequest;
use Cmat\Blog\Models\Post;
use Cmat\Blog\Repositories\Interfaces\CategoryInterface;
use Cmat\Blog\Repositories\Interfaces\PostInterface;
use Cmat\Blog\Repositories\Interfaces\TagInterface;
use Cmat\Blog\Services\StoreCategoryService;
use Cmat\Blog\Services\StoreTagService;
use Cmat\Blog\Tables\PostTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends BaseController
{
    use HasDeleteManyItemsTrait;

    public function __construct(
        protected PostInterface $postRepository,
        protected TagInterface $tagRepository,
        protected CategoryInterface $categoryRepository
    ) {
    }

    public function index(PostTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/blog::posts.menu_name'));

        return $dataTable->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/blog::posts.create'));

        return $formBuilder->create(PostForm::class)->renderForm();
    }

    public function store(
        PostRequest $request,
        StoreTagService $tagService,
        StoreCategoryService $categoryService,
        BaseHttpResponse $response
    ) {
        /**
         * @var Post $post
         */
        $post = $this->postRepository->createOrUpdate(
            array_merge($request->input(), [
                'author_id' => Auth::id(),
                'author_type' => User::class,
            ])
        );

        event(new CreatedContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

        $request->request->remove('seo_meta');

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        return $response
            ->setPreviousUrl(route('posts.index'))
            ->setNextUrl(route('posts.edit', $post->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $post = $this->postRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $post));

        page_title()->setTitle(trans('plugins/blog::posts.edit') . ' "' . $post->name . '"');

        return $formBuilder->create(PostForm::class, ['model' => $post])->renderForm();
    }

    public function update(
        $id,
        PostRequest $request,
        StoreTagService $tagService,
        StoreCategoryService $categoryService,
        BaseHttpResponse $response
    ) {
        $post = $this->postRepository->findOrFail($id);

        $post->fill($request->input());

        $this->postRepository->createOrUpdate($post);

        event(new UpdatedContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        return $response
            ->setPreviousUrl(route('posts.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int|string $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $post = $this->postRepository->findOrFail($id);
            $this->postRepository->delete($post);

            event(new DeletedContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

            return $response
                ->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        return $this->executeDeleteItems($request, $response, $this->postRepository, POST_MODULE_SCREEN_NAME);
    }

    public function getWidgetRecentPosts(Request $request, BaseHttpResponse $response)
    {
        $limit = (int)$request->input('paginate', 10);
        $limit = $limit > 0 ? $limit : 10;

        $posts = $this->postRepository->advancedGet([
            'with' => ['slugable'],
            'order_by' => ['created_at' => 'desc'],
            'paginate' => [
                'per_page' => $limit,
                'current_paged' => (int)$request->input('page', 1),
            ],
        ]);

        return $response
            ->setData(view('plugins/blog::posts.widgets.posts', compact('posts', 'limit'))->render());
    }
}
