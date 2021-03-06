<?php

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

Route::get('/', 'TopicsController@index')->name('root');

Auth::routes();

/* route UserController */
Route::resource('users','UsersController',['only'=>['show','update','edit']]);
Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::get('topics/{topic}/{slug?}','TopicsController@show')->name('topics.show');
Route::resource('categories','CategoriesController',['only' => ['show']]);
//话题中上传图片的路由
Route::post('upload_image','TopicsController@uploadImage')->name('topics.upload_image');
//对话题Topic回复的路由
Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);
//通知的路由
Route::resource('notifications','NotificationsController',['only'=> ['index']]);
//拒绝进入后台的路由
Route::get('permission-denied','PagesController@permissionDenied')->name('permission-denied');
