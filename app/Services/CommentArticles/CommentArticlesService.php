<?php

namespace App\Services\CommentArticles;

use App\Http\Requests\CommentArticle\CommentArticleGetRequest;
use App\Http\Requests\CommentArticle\CommentArticlePaginationRequest;
use App\Http\Requests\CommentArticle\CommentArticleRequest;
use App\Models\CommentArticle;
use App\Services\Service;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UnexpectedValueException;

class CommentArticlesService extends Service
{
  /**
   * validate Id
   *
   * @param   Number  $id
   *
   * @return  mixed
   */
  public function validateId($id): CommentArticle
  {
    $commentArticle = CommentArticle::find($id);

    if (!$commentArticle) {
      throw new NotFoundHttpException('Comment article data not found!');
    }

    return $commentArticle;
  }

  /**
   * get comment article query
   *
   * @return  CommentArticle
   */
  private function getCommentArticleQuery()
  {
    $commentArticleQuery = CommentArticle::query();

    $user = $this->getUser();

    $commentArticleQuery->where('user_id', $user->id);

    return $commentArticleQuery->with('article');
  }

  /**
   * get search query
   *
   * @param   CommentArticleGetRequest   $request
   * @param   CommentArticlePaginationRequest  $request
   *
   * @return  CommentArticle
   */
  private function getSearchQuery(CommentArticleGetRequest|CommentArticlePaginationRequest $request)
  {
    $articleQuery = $this->getCommentArticleQuery();

    $articleQuery->where('article_id', $request->article_id);

    return $articleQuery;
  }

  /**
   * get comment article data
   *
   * @param   CommentArticleGetRequest  $request
   *
   * @return  CommentArticle
   */
  public function get(CommentArticleGetRequest $request)
  {
    return $this->getSearchQuery($request)->get();
  }

  /**
   * get comment article data paginate
   *
   * @param   CommentArticlePaginationRequest  $request
   *
   * @return  CommentArticle
   */
  public function paginate(CommentArticlePaginationRequest $request)
  {
    $perPage = 10;

    if ($request->has('per_page')) {
      $perPage = $request->per_page;
    }

    return $this->getSearchQuery($request)->paginate($perPage);
  }

  /**
   * get comment article by id
   *
   * @param   Number  $id
   *
   * @return  CommentArticle
   */
  public function getById($id)
  {
    return $this->getCommentArticleQuery()
      ->where('id', '=', $id)
      ->first();
  }

  /**
   * get the comment article
   *
   * @param   CommentArticle  $commentArticle
   *
   * @return  CommentArticle
   */
  private function enrichCommentArticle(CommentArticle $commentArticle)
  {
    return $commentArticle->first();
  }

  /**
   * create or update the comment article
   *
   * @param   CommentArticleRequest  $request
   * @param   CommentArticle         $commentArticle
   * @param   Boolean          $forUpdate
   *
   * @return  CommentArticle
   */
  private function createOrUpdate(
    CommentArticleRequest $request,
    CommentArticle $commentArticle,
    $forUpdate = false
  ): CommentArticle {
    $user = $this->getUser();

    $commentArticle->article_id = $request->article_id;
    $commentArticle->user_id = $user->id;
    $commentArticle->comment = $request->comment;

    $result = $commentArticle->save();

    if (!$result) {
      $message = 'Gagal Menambahkan Comment Article';

      if ($forUpdate) {
        $message = 'Gagal Memperbarui Comment Article';
      }

      throw new UnexpectedValueException($message);
    }

    return $this->enrichCommentArticle($commentArticle);
  }

  /**
   * create the comment article
   *
   * @param   CommentArticleRequest  $request
   *
   * @return  CommentArticle
   */
  public function create(CommentArticleRequest $request)
  {
    return $this->createOrUpdate($request, new CommentArticle());
  }

  /**
   * edit the comment article
   *
   * @param   CommentArticleRequest  $request
   * @param   CommentArticle         $commentArticle
   *
   * @return  CommentArticle
   */
  public function edit(CommentArticleRequest $request, CommentArticle $commentArticle)
  {
    return $this->createOrUpdate($request, $commentArticle, true);
  }

  /**
   * delete the comment article
   *
   * @param   CommentArticle  $commentArticle
   *
   * @return  mixed
   */
  public function delete(CommentArticle $commentArticle)
  {
    if (!$commentArticle->delete()) {
      throw new UnexpectedValueException('Gagal Menghapus Comment Article');
    }

    return null;
  }
}
