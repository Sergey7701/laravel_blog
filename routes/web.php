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
if (strpos(filter_input(INPUT_SERVER, 'REQUEST_URI'), '/admin/') === false) {
    $router->bind('post', function ($value) {
        return App\Models\Article::where('slug', $value)->where('publish', 1)->first();
    });
} else {
    $router->bind('post', function ($value) {
        return App\Models\Article::where('slug', $value)->first();
    });
}
Route::get('/service', 'PushServiceController@form');
Route::post('/service', 'PushServiceController@send');
Auth::routes();
Route::resource('/posts', 'ArticleController');
Route::get('/', 'ArticleController@index');
Route::get('/home', 'HomeController@index');
Route::get('/posts/tags/{tag}/', 'TagController@index');
Route::get('/about', function () {
    return view('about');
});
Route::get('/contacts', function () {
    return view('contacts');
});
Route::group(['middleware' => 'role:administrator'], function() {
    Route::resource('/admin/posts', 'AdminArticleController');
    Route::get('/admin/feedbacks', 'Feedback@index')->middleware('auth');
    Route::get('/admin/{post}/versions', 'VersionController@index');
});

