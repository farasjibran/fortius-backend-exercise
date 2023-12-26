<?php

namespace App\Http\Requests\CommentArticle;

use App\Http\Requests\Pagination\PaginationRequest;

class CommentArticlePaginationRequest extends PaginationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            parent::rules(),
            with(new CommentArticleGetRequest())->rules(),
        ];
    }
}
