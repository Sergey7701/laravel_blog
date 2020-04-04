
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
Route::resource('/posts', 'ArticleController')->middleware('auth');
Route::get('/', 'ArticleController@index');
Route::get('/posts/', 'ArticleController@index');
Route::get('/posts/{post}/', 'ArticleController@show');