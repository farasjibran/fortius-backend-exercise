<?php

namespace App\Http\Resources\CommentArticle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentArticleListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'article_title' => $this->article->title,
            'comment' => $this->comment,
        ];
    }
}
