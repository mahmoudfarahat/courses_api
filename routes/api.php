<?php

use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\BioController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::get('category/{id}', [CategoryController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('bio', BioController::class);
    Route::resource('category', CategoryController::class );
    Route::resource('playlist', PlaylistController::class);
    Route::resource('article', ArticlesController::class);
    Route::resource('book', BookController::class);
    Route::resource('media', MediaController::class);
    //patch
    Route::put('/answer/delete/{id}', [QuestionController::class, 'answerDestroy']);
    Route::put('question/{id}', [QuestionController::class, 'update']);
    Route::put('/answer/change/{id}', [QuestionController::class, 'changeAnswer']);
    Route::post('logout', [UserController::class, 'logout']);


});

Route::get('category', [CategoryController::class, 'index']);


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);



Route::get('bio', [BioController::class, 'index']);
Route::get('bio/{id}', [BioController::class, 'show']);







Route::get('playlist', [PlaylistController::class, 'index']);

 Route::get('playlists/{slug}', [PlaylistController::class, 'show']);


Route::post('playlist/search', [PlaylistController::class, 'search']);


Route::get('article', [ArticlesController::class, 'index']);
Route::get('articles/{slug}', [ArticlesController::class, 'show']);

Route::get('book', [BookController::class, 'index']);
Route::get('book/{slug}', [BookController::class, 'show']);

Route::get('media', [MediaController::class, 'index']);
Route::get('media/{slug}', [MediaController::class, 'show']);


Route::get('book/download', [BookController::class, 'download']);
Route::post('book/search', [BookController::class, 'search']);

Route::resource('question', QuestionController::class);
