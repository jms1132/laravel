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

Route::get('/', function () {
	return view('welcome');
});

//자유게시판
Route::get('/free', 'ArticlesController@index')->name('free.index')->middleware('auth');
Route::get('/free/search', 'ArticlesController@search');
Route::get('/free/create', 'ArticlesController@create');
Route::post('/free', 'ArticlesController@store');
Route::get('/free/{article}', 'ArticlesController@show')->name('articles.show');
Route::get('/free/{article}/edit', 'ArticlesController@edit')->name('articles.edit');
Route::put('/free/{article}', 'ArticlesController@update');
Route::get('/free/{article}/download', 'ArticlesController@download')->name('articles.download');
Route::delete('/free/{article}', 'ArticlesController@destroy');

//공지게시판
Route::get('/notice', 'ArticlesController@index')->name('notice.index')->middleware('auth');
Route::get('/notice/search', 'ArticlesController@search');
Route::get('/notice/create', 'ArticlesController@create');
Route::post('/notice', 'ArticlesController@store');
Route::get('/notice/{article}', 'ArticlesController@show')->name('articles.show');
Route::get('/notice/{article}/edit', 'ArticlesController@edit')->name('articles.edit');
Route::put('/notice/{article}', 'ArticlesController@update');
Route::get('/notice/{article}/download', 'ArticlesController@download')->name('articles.download');
Route::delete('/notice/{article}', 'ArticlesController@destroy');

//사용자
Route::get('/myPage/{user}/myInfoEdit', 'UserController@myInfoEdit')->name('myPage.myInfoEdit');
Route::put('/myPage/{user}', 'UserController@myInfoUpdate');
Route::get('/myPage/myArticles', 'UserController@myArticles');
Route::get('/myPage/myArticles/search', 'UserController@myArticlesSearch');

//관리자
Route::get('/user/{user}', 'UserController@articles');
Route::get('/user', 'UserController@index')->name('user.index');
Route::delete('/user/destroy', 'UserController@destroy');
Route::get('/user/search', 'UserController@userSearch');
Route::get('/user/{user}/delete', 'UserController@userDelete');
Route::post('/user/multi_delete','UserController@multiDelete');


// GET/articles = 컬렉션
// GET/articles/:id = 싱글 아티클
// POST/articles= = 생성 
// PUT/articles/:id
// DELETE/articles/:id

Auth::routes();
