<?php
use Illuminate\Support\Facades\Route;
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
Auth::routes();
 Route::get('/', 'Article@list');
Route::get('/home', 'HomeController@index');
Route::get('/about', function () {
    return view('about');
});
Route::get('/posts/create', function () {
    return view('createArticle', [
        'title' => 'Новая статья',
    ]);
});
Route::get('/posts/{article}', function (App\Models\Article $article){
    return view('show', [
        'article' => $article,
    ]);
});
Route::post('/posts/create', 'Article@create');
Route::get('/contacts', function () {
    return view('contacts');
});
Route::post('/contacts', 'Feedback@new');
Route::get('/admin/feedbacks', 'Feedback@list');
