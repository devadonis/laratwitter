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

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('home', 'HomeController@index')->name('home');
    
    // User routes
    Route::prefix('users')->group(function () {
        Route::get('{user}', 'UserController@show')->name('user.show');
        Route::get('{user}/follow', 'UserController@follow')->name('user.follow');
        Route::get('{user}/unfollow', 'UserController@unfollow')->name('user.unfollow');
    });

    // Post routes
    Route::prefix('posts')->group(function () {
        Route::get('/', 'PostController@show')->name('posts.show');
        Route::post('save', 'PostController@store');
    });

    // Task routes
    Route::prefix('tasks')->group(function () {
        Route::post('/', 'TaskController@create')->name('task.create');
        Route::get('/','TaskController@read')->name('task.read');
        Route::patch('/{id}/status','TaskController@updateStatus')->name('task.updateStatus');
        Route::patch('{id}/content','TaskController@updateContent')->name('task.updateContent');
        Route::put('updateAll','TaskController@updateOrder')->name('task.reorder');
        Route::delete('{id}','TaskController@destroy')->name('task.destroy');
    });
});
