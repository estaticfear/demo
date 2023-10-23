<?php

namespace Cmat\Member\Http\Controllers;

use Assets;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Events\BeforeEditContentEvent;
use Cmat\Base\Events\CreatedContentEvent;
use Cmat\Base\Events\UpdatedContentEvent;
use Cmat\Base\Forms\FormBuilder;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Blog\Models\Post;
use Cmat\Blog\Repositories\Interfaces\PostInterface;
use Cmat\Blog\Repositories\Interfaces\TagInterface;
use Cmat\Blog\Services\StoreCategoryService;
use Cmat\Blog\Services\StoreTagService;
use Cmat\Member\Forms\PostForm;
use Cmat\Member\Http\Requests\PostRequest;
use Cmat\Member\Models\Member;
use Cmat\Member\Repositories\Interfaces\MemberActivityLogInterface;
use Cmat\Member\Repositories\Interfaces\MemberInterface;
use Cmat\Member\Tables\PostTable;
use EmailHandler;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RvMedia;
use SeoHelper;

class PostController extends Controller
{
    public function __construct(
        Repository $config,
        protected MemberInterface $memberRepository,
        protected PostInterface $postRepository,
        protected MemberActivityLogInterface $activityLogRepository
    ) {
        Assets::setConfig($config->get('plugins.member.assets', []));
    }

    public function index(PostTable $postTable)
    {
        SeoHelper::setTitle(trans('plugins/blog::posts.posts'));

        return $postTable->render('plugins/member::table.base');
    }

    public function create(FormBuilder $formBuilder)
    {
        SeoHelper::setTitle(trans('plugins/member::member.write_a_post'));

        return $formBuilder->create(PostForm::class)->renderForm();
    }

    public function store(
        PostRequest $request,
        StoreTagService $tagService,
        StoreCategoryService $categoryService,
        BaseHttpResponse $response
    ) {
        $this->processRequestData($request);

        /**
         * @var Post $post
         */
        $post = $this->postRepository->createOrUpdate(
            array_merge($request->except('status'), [
                'author_id' => auth('member')->id(),
                'author_type' => Member::class,
                'status' => BaseStatusEnum::PENDING,
            ])
        );

        event(new CreatedContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

        $this->activityLogRepository->createOrUpdate([
            'action' => 'create_post',
            'reference_name' => $post->name,
            'reference_url' => route('public.member.posts.edit', $post->id),
        ]);

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        EmailHandler::setModule(MEMBER_MODULE_SCREEN_NAME)
            ->setVariableValues([
                'post_name' => $post->name,
                'post_url' => route('posts.edit', $post->id),
                'post_author' => $post->author->name,
            ])
            ->sendUsingTemplate('new-pending-post');

        return $response
            ->setPreviousUrl(route('public.member.posts.index'))
            ->setNextUrl(route('public.member.posts.edit', $post->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int|string $id, FormBuilder $formBuilder, Request $request)
    {
        $post = $this->postRepository->getFirstBy([
            'id' => $id,
            'author_id' => auth('member')->id(),
            'author_type' => Member::class,
        ]);

        if (! $post) {
            abort(404);
        }

        event(new BeforeEditContentEvent($request, $post));

        SeoHelper::setTitle(trans('plugins/blog::posts.edit') . ' "' . $post->name . '"');

        return $formBuilder
            ->create(PostForm::class, ['model' => $post])
            ->renderForm();
    }

    public function update(
        int|string $id,
        PostRequest $request,
        StoreTagService $tagService,
        StoreCategoryService $categoryService,
        BaseHttpResponse $response
    ) {
        $post = $this->postRepository->getFirstBy([
            'id' => $id,
            'author_id' => auth('member')->id(),
            'author_type' => Member::class,
        ]);

        if (! $post) {
            abort(404);
        }

        $this->processRequestData($request);

        $post->fill($request->except('status'));

        $this->postRepository->createOrUpdate($post);

        event(new UpdatedContentEvent(POST_MODULE_SCREEN_NAME, $request, $post));

        $this->activityLogRepository->createOrUpdate([
            'action' => 'update_post',
            'reference_name' => $post->name,
            'reference_url' => route('public.member.posts.edit', $post->id),
        ]);

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        return $response
            ->setPreviousUrl(route('public.member.posts.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    protected function processRequestData(Request $request): Request
    {
        $account = auth('member')->user();

        if ($request->hasFile('image_input')) {
            $result = RvMedia::handleUpload($request->file('image_input'), 0, $account->upload_folder);
            if (! $result['error']) {
                $file = $result['data'];
                $request->merge(['image' => $file->url]);
            }
        }

        $shortcodeCompiler = shortcode()->getCompiler();

        $request->merge([
            'content' => $shortcodeCompiler->strip(
                $request->input('content'),
                $shortcodeCompiler->whitelistShortcodes()
            ),
        ]);

        $except = [
            'status',
            'is_featured',
        ];

        foreach ($except as $item) {
            $request->request->remove($item);
        }

        return $request;
    }

    public function destroy(int|string $id, BaseHttpResponse $response)
    {
        $post = $this->postRepository->getFirstBy([
            'id' => $id,
            'author_id' => auth('member')->id(),
            'author_type' => Member::class,
        ]);

        if (! $post) {
            abort(404);
        }

        $this->postRepository->delete($post);

        $this->activityLogRepository->createOrUpdate([
            'action' => 'delete_post',
            'reference_name' => $post->name,
        ]);

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getAllTags(TagInterface $tagRepository)
    {
        return $tagRepository->pluck('name');
    }
}
