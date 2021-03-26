<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\RootController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\SiteMapController;
use App\Http\Controllers\CommentsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [RootController::class, 'index'])->name('root');
Route::get('/t/{tag}', [RootController::class, 'tag'])->name('root.tag');
Route::get('/a/{article}', [RootController::class, 'article'])->name('root.article');
Route::get('/i/{item}', [RootController::class, 'item'])->name('root.item');
Route::get('/t/{tag}/i/{item}', [RootController::class, 'item'])->name('root.tag.item');
Route::get('/t/{tag}/a/{article}', [RootController::class, 'article'])->name('root.tag.article');
Route::post('/i/{item}/comments', [CommentsController::class, 'user_store'])->name('comments.user_store');

Auth::routes([
    'register' => false // ユーザ登録機能をオフに切替
]);




Route::group(['middleware' => ['auth', 'verified', ]], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // 管理画面内
    Route::resource('/comments', CommentsController::class);

    Route::resource('/items', ItemsController::class);

    Route::resource('/tags', TagsController::class);

    Route::resource('/articles', ArticlesController::class);

    Route::resource('/users', UsersController::class);
    Route::get('/users/{user}/edit_pass', [UsersController::class, 'edit_pass'])->name('users.edit_pass');
    Route::patch('/users/{user}/update_pass', [UsersController::class, 'update_pass'])->name('users.update_pass');
});

Route::get('/sitemap.xml', [SiteMapController::class, 'sitemap'])->name('sitemap');