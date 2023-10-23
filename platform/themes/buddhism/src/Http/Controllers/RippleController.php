<?php

namespace Theme\Ripple\Http\Controllers;

use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Blog\Repositories\Interfaces\PostInterface;
use Cmat\Theme\Http\Controllers\PublicController;
use Illuminate\Http\Request;
use SlugHelper;
use Theme;
use BaseHelper;
use Cmat\Page\Models\Page;
use Cmat\Blog\Models\Category;
use Cmat\Theme\Events\RenderingSingleEvent;
use Illuminate\Support\Arr;
class RippleController extends PublicController
{
    public function getIndex()
    {
        return parent::getIndex();
    }

    public function getView(string $key = null)
    {
        // return parent::getView($key);
        if (empty($key)) {
            return $this->getIndex();
        }

        $slug = SlugHelper::getSlug($key, '');

        if (! $slug) {
            abort(404);
        }

        if (defined('PAGE_MODULE_SCREEN_NAME')) {
            if ($slug->reference_type == Page::class && BaseHelper::isHomepage($slug->reference_id)) {
                return redirect()->route('public.index');
            }
        }

        $result = apply_filters(BASE_FILTER_PUBLIC_SINGLE_DATA, $slug);

        if (isset($result['slug']) && $result['slug'] !== $key) {
            return redirect()->route('public.single', $result['slug']);
        }

        event(new RenderingSingleEvent($slug));

        if (! empty($result) && is_array($result)) {
            if ($slug->reference_type == Category::class) {
                return Theme::layout('no-sidebar-no-breadcrumbs')->scope($result['view'], $result['data'], Arr::get($result, 'default_view'))->render();
            } else {
                return Theme::scope($result['view'], $result['data'], Arr::get($result, 'default_view'))->render();
            }

        }

        abort(404);
    }

    public function getSiteMap()
    {
        return parent::getSiteMap();
    }

    /**
     * Search post
     *
     * @bodyParam q string required The search keyword.
     *
     * @group Blog
     */
    public function getSearch(Request $request, PostInterface $postRepository, BaseHttpResponse $response)
    {
        $query = $request->input('q');

        if (! empty($query)) {
            $posts = $postRepository->getSearch($query);

            $data = [
                'items' => Theme::partial('search', compact('posts')),
                'query' => $query,
                'count' => $posts->count(),
            ];

            if ($data['count'] > 0) {
                return $response->setData(apply_filters(BASE_FILTER_SET_DATA_SEARCH, $data, 10, 1));
            }
        }

        return $response
            ->setError()
            ->setMessage(__('No results found, please try with different keywords.'));
    }
}
