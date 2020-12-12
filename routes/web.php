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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('tweet/save', 'PostController@store');
Route::get('users/{user}', 'UserController@show')->name('user.show');
Route::get('users/{user}/follow', 'UserController@follow')->name('user.follow');
Route::get('users/{user}/unfollow', 'UserController@unfollow')->name('user.unfollow');
Route::get('posts', 'PostController@index')->name('posts.index');