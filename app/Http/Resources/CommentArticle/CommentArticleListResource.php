<?php

namespace App\Http\Resources\CommentArticle;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
            'comment' => $this->comment,
            'comment_from' => $this->user->name,
            'is_my_comment' => $this->user_id == Auth::user()->id,
            'created_at' => $this->created_at
        ];
    }
}
