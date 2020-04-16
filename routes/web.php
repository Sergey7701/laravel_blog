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
$router->bind('post', function ($value) {
    return App\Models\Article::where('slug', $value)->where('publish', 1)->first();
});
Auth::routes();
Route::resource('/posts', 'ArticleController');
Route::get('/', 'ArticleController@index');
Route::get('/home', 'HomeController@index');
//Route::get('/posts/', 'ArticleController@index');
//Route::get('/posts/{post}/', 'ArticleController@show');
Route::get('/posts/tags/{tag}/', 'TagController@index');
Route::get('/about', function () {
    return view('about');
});
Route::get('/contacts', function () {
    return view('contacts');
});
Route::get('/admin/feedbacks', 'Feedback@index')->middleware('auth');
