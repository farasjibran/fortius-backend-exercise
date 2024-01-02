<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleGetRequest;
use App\Http\Requests\Article\ArticlePaginationRequest;
use App\Http\Requests\Article\ArticleRequest;
use App\Http\Resources\Article\ArticleListResource;
use App\Http\Resources\Article\ArticlePaginationResource;
use App\Http\Resources\Article\ArticleResource;
use App\Services\Articles\ArticlesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    protected $articleService;

    /**
     * constructor
     *
     * @param   ArticlesService  $articleService
     *
     * @return  mixed
     */
    public function __construct(ArticlesService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param   ArticleGetRequest  $request
     *
     * @return  JsonResponse
     */
    public function index(ArticleGetRequest $request)
    {
        $response = ArticleListResource::collection($this->articleService->get($request));

        return $this->ok($response);
    }

    /**
     * Display a listing of the resource with pagination
     *
     * @param   ArticlePaginationRequest  $request
     *
     * @return  JsonResponse
     */
    public function paginate(ArticlePaginationRequest $request)
    {
        $response = new ArticlePaginationResource($this->articleService->paginate($request));

        return $this->ok($response);
    }

    /**
     * Display the resource
     *
     * @param   ArticleGetRequest  $request
     *
     * @return  JsonResponse
     */
    public function getById(string $id)
    {
        $response = new ArticleResource($this->articleService->getById($id));

        return $this->ok($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param   ArticleRequest  $request
     *
     * @return  JsonResponse
     */
    public function store(ArticleRequest $request)
    {
        $response = new ArticleResource($this->articleService->create($request));

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
            $this->articleService->validateId($id);

            return new ArticleResource($this->articleService->getById($id));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   ArticleRequest  $request
     * @param   string          $id
     *
     * @return  JsonResponse
     */
    public function update(ArticleRequest $request, string $id)
    {
        $article = $this->articleService->validateId($id);

        $response = new ArticleResource($this->articleService->edit($request, $article));

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
            $article = $this->articleService->validateId($id);

            return $this->articleService->delete($article);
        });
    }

    /**
     * Display a listing of the resource of my article.
     *
     * @param   ArticleGetRequest  $request
     *
     * @return  JsonResponse
     */
    public function getMyArticleData(ArticleGetRequest $request)
    {
        $response = ArticleListResource::collection($this->articleService->getMyArticle($request));

        return $this->ok($response);
    }

    /**
     * Display a listing of the resource of my article with pagination
     *
     * @param   ArticlePaginationRequest  $request
     *
     * @return  JsonResponse
     */
    public function paginateMyArticleData(ArticlePaginationRequest $request)
    {
        $response = new ArticlePaginationResource($this->articleService->paginateMyArticle($request));

        return $this->ok($response);
    }
}
