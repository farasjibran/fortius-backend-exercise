<?php

namespace App\Services\Articles;

use App\Http\Requests\Article\ArticleGetRequest;
use App\Http\Requests\Article\ArticlePaginationRequest;
use App\Http\Requests\Article\ArticleRequest;
use App\Models\Article;
use App\Services\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UnexpectedValueException;

class ArticlesService extends Service
{
  /**
   * validate Id
   *
   * @param   Number  $id
   *
   * @return  mixed
   */
  public function validateId($id): Article
  {
    $article = Article::find($id);

    if (!$article) {
      throw new NotFoundHttpException('Article data not found!');
    }

    return $article;
  }

  /**
   * get article query
   *
   * @return  Article
   */
  private function getArticleQuery()
  {
    $articleQuery = Article::query();

    $user = $this->getUser();

    $articleQuery->where('user_id', $user->id);

    return $articleQuery->with('commentArticle');
  }

  /**
   * get search query
   *
   * @param   ArticleGetRequest   $request
   * @param   ArticlePaginationRequest  $request
   *
   * @return  Article
   */
  private function getSearchQuery(ArticleGetRequest|ArticlePaginationRequest $request)
  {
    $articleQuery = $this->getArticleQuery();

    if ($request->has('search')) {
      $articleQuery->where('title', 'like', '%' . $request->search . '%');
    }

    return $articleQuery;
  }

  /**
   * get article data
   *
   * @param   ArticleGetRequest  $request
   *
   * @return  Article
   */
  public function get(ArticleGetRequest $request)
  {
    return $this->getSearchQuery($request)->get();
  }

  /**
   * get article data paginate
   *
   * @param   ArticlePaginationRequest  $request
   *
   * @return  Article
   */
  public function paginate(ArticlePaginationRequest $request)
  {
    $perPage = 10;

    if ($request->has('per_page')) {
      $perPage = $request->per_page;
    }

    return $this->getSearchQuery($request)->paginate($perPage);
  }

  /**
   * get article by id
   *
   * @param   Number  $id
   *
   * @return  Article
   */
  public function getById($id)
  {
    return $this->getArticleQuery()
      ->where('id', '=', $id)
      ->first();
  }

  /**
   * get the article
   *
   * @param   Article  $article
   *
   * @return  Article
   */
  private function enrichArticle(Article $article)
  {
    return $article->first();
  }

  /**
   * create or update the article
   *
   * @param   ArticleRequest  $request
   * @param   Article         $article
   * @param   Boolean          $forUpdate
   *
   * @return  Article
   */
  private function createOrUpdate(ArticleRequest $request, Article $article, $forUpdate = false): Article
  {
    $user = $this->getUser();

    $article->title = $request->title;
    $article->description = $request->description;
    $article->user_id = $user->id;

    $result = $article->save();

    if (!$result) {
      $message = 'Gagal Menambahkan Article';

      if ($forUpdate) {
        $message = 'Gagal Memperbarui Article';
      }

      throw new UnexpectedValueException($message);
    }

    return $this->enrichArticle($article);
  }

  /**
   * create the article
   *
   * @param   ArticleRequest  $request
   *
   * @return  Article
   */
  public function create(ArticleRequest $request)
  {
    return $this->createOrUpdate($request, new Article());
  }

  /**
   * edit the article
   *
   * @param   ArticleRequest  $request
   * @param   Article         $article
   *
   * @return  Article
   */
  public function edit(ArticleRequest $request, Article $article)
  {
    return $this->createOrUpdate($request, $article, true);
  }

  /**
   * delete the article
   *
   * @param   Article  $article
   *
   * @return  mixed
   */
  public function delete(Article $article)
  {
    if (!$article->delete()) {
      throw new UnexpectedValueException('Gagal Menghapus Article');
    }

    return null;
  }
}
