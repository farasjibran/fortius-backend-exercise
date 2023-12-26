<?php

namespace App\Http\Resources\CommentArticle;

use App\Http\Resources\Pagination\PaginationResource;
use Illuminate\Http\Request;

class CommentArticlePaginationResource extends PaginationResource
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
            ['items' => CommentArticleListResource::collection($this->collection)]
        );
    }
}
