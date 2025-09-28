<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\StatisticController;
use Illuminate\Support\Facades\Route;


Route::post("/poll-voting", [\App\Http\Controllers\Web\PollController::class, "pollVoting"]);
Route::post("/news/list-action-items", [\App\Http\Controllers\Web\HomeController::class, "getNewsList"]);
Route::get("/newsletter", [\App\Http\Controllers\Web\HomeController::class, "newsletter"]);
Route::post("/translate", [\App\Http\Controllers\Admin\HomeController::class, "translate"])->name('translate');
Route::post("/translate-title", [\App\Http\Controllers\Admin\HomeController::class, "translateTitle"])->name('translateTitle');
Route::post("/disabled-site", [\App\Http\Controllers\Admin\ManagementController::class, "disabledSite"])->name('disabledSite');
Route::post("/trs-tag-create",[ \App\Http\Controllers\Admin\TagController::class,"trsTagCreate"])->name('tags.trsTagCreate');


Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api'], function () {
    Route::resource('posts', 'PostController');
    Route::get('home/get-news-home', 'HomeController@getNewsHome');
    Route::get('get-post/{id}', 'HomeController@getPostId');
    Route::get('get-category/{id}', 'HomeController@getCategoryId');
    Route::get('get-search', 'HomeController@getSearch');
    Route::get('get-tag/{id}', 'HomeController@getTags');
    Route::get('get-videos', 'HomeController@getVideoPage');
    Route::get('get-photos', 'HomeController@getPhotoPage');
    Route::get('exchange-rate', 'HomeController@getExchangeRate');
    Route::get('get-most-used-tags-list', 'HomeController@getMostUsedTags');
    Route::get('get-editor-data/{id}', 'HomeController@getEditorPosts');
    Route::post('send-appeal-form', 'HomeController@sendAppeal');
    Route::post('auth/login',[AuthController::class,'login']);
    Route::middleware('auth:api')->get('me', [AuthController::class, 'me']);
    Route::post('auth/register', [AuthController::class,'register']);


});
Route::group(['prefix' => 'v1'], function () {
    Route::get('posts', [PostController::class, 'index']);
    Route::get('get-management-persons', [PostController::class, 'getManagementPersons']);
    Route::get('statistics', [StatisticController::class, 'index']);
    Route::get('get-post/{id}', [PostController::class, 'getPostId']);
});
