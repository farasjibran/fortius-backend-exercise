<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\User\ArticleController;
use App\Http\Controllers\User\CommentArticleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['cors', 'json.response']], function () {
    // Authentication
    Route::post('/login', [ApiAuthController::class, 'login'])
        ->name('login.api');
    Route::post('/register', [ApiAuthController::class, 'register'])
        ->name('register.api');

    Route::middleware('auth:api')->group(function () {
        // Authentication
        Route::post('/logout', [ApiAuthController::class, 'logout'])
            ->name('logout.api');

        // Article
        Route::get('/article/paginate', [ArticleController::class, 'paginate'])
            ->name('article.paginate');
        Route::resource('article', ArticleController::class);

        // Comment Article
        Route::get('/comment-article/paginate', [CommentArticleController::class, 'paginate'])
            ->name('comment-article.paginate');
        Route::resource('comment-article', CommentArticleController::class);
    });
});
