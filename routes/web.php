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

Route::get('/test', function() {
//    Artisan::call('optimize:clear');
    event(new \App\Events\ArticleCreated(App\Models\Article::find(2)));
//  event(new \App\Events\SomethingHappens('lalala'));
//    event(new \App\Events\Nothing);
    //App\Jobs\StatisticReport::dispatch()->onQueue('report')->delay(now()->addMinutes(1));
});
Route::get('/service', 'PushServiceController@form');
Route::post('/service', 'PushServiceController@send');
Auth::routes();
Route::resource('/posts', 'ArticleController');
Route::get('/', 'ArticleController@index');
Route::get('/home', 'HomeController@index');
Route::get('/posts/tags/{tag}/', 'TagController@index');
Route::get('/about', function () {
    session(['admin' => false]);
    return view('about');
});
Route::get('/contacts', function() {
    session(['admin' => false]);
    return view('contacts');
});
Route::get('/statistic', 'StatisticController@index');
Route::resource('/news', 'NewsController');
Route::resource('/news/{entry}/comment', 'CommentController');
Route::resource('/posts/{entry}/comment', 'CommentController');
//Admin Section
Route::group(['middleware' => 'permission:manage-articles'], function() {
    Route::get('/admin/feedbacks', 'Feedback@index')->middleware('auth');
    Route::resource('/admin/posts', 'AdminArticleController');
    Route::get('/admin/posts/{post}/versions', 'VersionController@indexArticles');
    Route::get('/admin/news/{news}/versions', 'VersionController@indexNews');
    Route::get('/admin', function () {
        session(['admin' => true]);
        return redirect('/admin/posts');
    });
    Route::resource('/admin/news', 'AdminNewsController');
    Route::get('/admin/report', 'AdminReportController@showForm');
    Route::post('/admin/report', 'AdminReportController@makeReport');
});


