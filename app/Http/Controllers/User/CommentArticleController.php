<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentArticle\CommentArticleGetRequest;
use App\Http\Requests\CommentArticle\CommentArticlePaginationRequest;
use App\Http\Requests\CommentArticle\CommentArticleRequest;
use App\Http\Resources\CommentArticle\CommentArticleListResource;
use App\Http\Resources\CommentArticle\CommentArticlePaginationResource;
use App\Http\Resources\CommentArticle\CommentArticleResource;
use App\Services\CommentArticles\CommentArticlesService;

class CommentArticleController extends Controller
{
    protected $commentArticleService;

    /**
     * contructor
     *
     * @param   CommentArticlesService  $commentArticleService
     *
     * @return  mixed
     */
    public function __construct(CommentArticlesService $commentArticleService)
    {
        $this->commentArticleService = $commentArticleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param   CommentArticleGetRequest  $request
     *
     * @return  JsonResponse
     */
    public function index(CommentArticleGetRequest $request)
    {
        $response = CommentArticleListResource::collection($this->commentArticleService->get($request));

        return $this->ok($response);
    }

    /**
     * Display a listing of the resource with pagination
     *
     * @param   CommentArticlePaginationRequest  $request
     *
     * @return  JsonResponse
     */
    public function paginate(CommentArticlePaginationRequest $request)
    {
        $response = CommentArticlePaginationResource::collection($this->commentArticleService->paginate($request));

        return $this->ok($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param   CommentArticleRequest  $request
     *
     * @return  JsonResponse
     */
    public function store(CommentArticleRequest $request)
    {
        $response = new CommentArticleResource($this->commentArticleService->create($request));

        return $this->ok($response);
    }

    /**
     * Display the specified resource.
     *
     * @param   string  $id
     *
     * @return  JsonResponse
     */
    public function show(string $id)
    {
        return $this->handle(function () use ($id) {
            $this->commentArticleService->validateId($id);

            return new CommentArticleResource($this->commentArticleService->getById($id));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   CommentArticleRequest  $request
     * @param   string                 $id
     *
     * @return  JsonResponse
     */
    public function update(CommentArticleRequest $request, string $id)
    {
        $commentArticle = $this->commentArticleService->validateId($id);

        $response = new CommentArticleResource($this->commentArticleService->edit($request, $commentArticle));

        return $this->ok($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   string  $id
     *
     * @return  JsonResponse
     */
    public function destroy(string $id)
    {
        return $this->handle(function () use ($id) {
            $commentArticle = $this->commentArticleService->validateId($id);

            return $this->commentArticleService->delete($commentArticle);
        });
    }
}
