<?php

namespace Cmat\Blog\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Blog\Http\Resources\CategoryResource;
use Cmat\Blog\Http\Resources\ListCategoryResource;
use Cmat\Blog\Models\Category;
use Cmat\Blog\Repositories\Interfaces\CategoryInterface;
use Cmat\Blog\Supports\FilterCategory;
use Illuminate\Http\Request;
use SlugHelper;

class CategoryController extends Controller
{
    public function __construct(protected CategoryInterface $categoryRepository)
    {
    }

    /**
     * List categories
     *
     * @group Blog
     */
    public function index(Request $request, BaseHttpResponse $response)
    {
        $data = $this->categoryRepository
            ->advancedGet([
                'with' => ['slugable'],
                'condition' => ['status' => BaseStatusEnum::PUBLISHED],
                'paginate' => [
                    'per_page' => (int)$request->input('per_page', 10),
                    'current_paged' => (int)$request->input('page', 1),
                ],
            ]);

        return $response
            ->setData(ListCategoryResource::collection($data))
            ->toApiResponse();
    }

    /**
     * Filters categories
     *
     * @group Blog
     */
    public function getFilters(Request $request, BaseHttpResponse $response)
    {
        $filters = FilterCategory::setFilters($request->input());
        $data = $this->categoryRepository->getFilters($filters);

        return $response
            ->setData(CategoryResource::collection($data))
            ->toApiResponse();
    }

    /**
     * Get category by slug
     *
     * @group Blog
     * @queryParam slug Find by slug of category.
     */
    public function findBySlug(string $slug, BaseHttpResponse $response)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Category::class));

        if (! $slug) {
            return $response->setError()->setCode(404)->setMessage('Not found');
        }

        $category = $this->categoryRepository->getCategoryById($slug->reference_id);

        if (! $category) {
            return $response->setError()->setCode(404)->setMessage('Not found');
        }

        return $response
            ->setData(new ListCategoryResource($category))
            ->toApiResponse();
    }
}
