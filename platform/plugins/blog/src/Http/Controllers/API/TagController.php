<?php

namespace Cmat\Blog\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Cmat\Base\Enums\BaseStatusEnum;
use Cmat\Base\Http\Responses\BaseHttpResponse;
use Cmat\Blog\Http\Resources\TagResource;
use Cmat\Blog\Repositories\Interfaces\TagInterface;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(protected TagInterface $tagRepository)
    {
    }

    /**
     * List tags
     *
     * @group Blog
     */
    public function index(Request $request, BaseHttpResponse $response)
    {
        $data = $this->tagRepository
            ->advancedGet([
                'with' => ['slugable'],
                'condition' => ['status' => BaseStatusEnum::PUBLISHED],
                'paginate' => [
                    'per_page' => (int)$request->input('per_page', 10),
                    'current_paged' => (int)$request->input('page', 1),
                ],
            ]);

        return $response
            ->setData(TagResource::collection($data))
            ->toApiResponse();
    }
}
