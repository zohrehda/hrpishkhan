<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'drafts', 'middleware' => [/*'auth'*/]], function () {

    Route::get('/categories', 'DraftCategoriesController@index')->name('api.draft.categories.index');
    Route::post('/categories', 'DraftCategoriesController@store')->name('api.draft.categories.store');
    Route::delete('/categories/{id}', 'DraftCategoriesController@destroy')->name('api.draft.categories.destroy');

    Route::get('/', 'DraftsController@index')->name('api.draft.index');
    Route::get('/{id}', 'DraftsController@show')->name('api.draft.indefx');
    Route::post('/', 'DraftsController@store')->name('api.draft.store');
    Route::delete('/{id}', 'DraftsController@destroy')->name('api.draft.destroy');

});



