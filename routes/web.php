<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'MainController@index');

Route::get('register','RegisterController@index');

Route::post('register','RegisterController@store');

Route::get('login','LoginController@index');

Route::get('home','HomeController@index');

Route::post('home','HomeController@login');

Route::get('upload','GameUploadController@index');

Route::post('upload','GameUploadController@store');

Route::post('upload2','GameUploadController@save');

Route::get('upload/0','GameUploadController@gameupload');

Route::get('upload/1','GameUploadController@gamedesc');

Route::get('logout','MainController@logout');

Route::get('userprofile','UserProfileController@index');

Route::post('userprofile','UserProfileController@updatePic');

Route::get('game/{id}','GameProfileController@index');

Route::post('game/{id}','GameProfileController@addcomment');

Route::get('game/{id}/like','GameProfileController@gamelike');

Route::get('game/{id}/dislike','GameProfileController@gamedislike');

Route::get('play/{name}','PlayController@index');

Route::get('game/comment_like/{gcid}/{cid}','GameProfileController@commentlike');

Route::post('game','GameProfileController@search');

Route::get('ranking','UserRankingController@index');

Route::get('about','AboutController@index');

Route::get('filter/puzzle','MainController@puzzle');

Route::get('filter/action','MainController@action');

Route::get('filter/strategy','MainController@strategy');