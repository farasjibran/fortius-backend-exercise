<?php

namespace App\Http\Resources\Article;

use App\Http\Resources\Pagination\PaginationResource;
use Illuminate\Http\Request;

class ArticlePaginationResource extends PaginationResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(
            parent::toArray($request),
            ['items' => ArticleListResource::collection($this->collection)]
        );
    }
}
