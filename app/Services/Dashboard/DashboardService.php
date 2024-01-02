<?php

namespace App\Services\Dashboard;

use App\Models\Article;
use App\Models\CommentArticle;
use App\Services\Service;

class DashboardService extends Service
{
  /**
   * get article query
   *
   * @return  Article
   */
  private function getArticleQuery()
  {
    return Article::query();
  }

  /**
   * get article query
   *
   * @return  CommentArticle
   */
  private function getMyArticleQuery()
  {
    $user = $this->getUser();

    $article = Article::query();

    return $article->where('user_id', $user->id);
  }

  /**
   * get dashboard data
   *
   * @return  Object
   */
  public function get()
  {
    $article = array(
      'all' => $this->getArticleQuery()->count(),
      'myArticle' => $this->getMyArticleQuery()->count()
    );

    return (object) [
      'article' => $article,
    ];
  }
}
