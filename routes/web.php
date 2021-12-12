<?php

Auth::routes(['verify' => false, 'register' => false, 'reset' => false]);
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::redirect('/', 'login');

Route::group(['prefix' => 'panel', 'middleware' => ['auth:web'/*, 'verified'*/]], function () {
    Route::get('dashboard', 'RequisitionController@index')->name('dashboard');
    Route::group(['prefix' => 'requisitions'], function () {
        Route::get('create', 'RequisitionController@create')->name('requisitions.create');
        Route::post('store', 'RequisitionController@store')->name('requisitions.store');
        Route::get('{requisition}/edit', 'RequisitionController@edit')->name('requisitions.edit');
        Route::post('update', 'RequisitionController@update')->name('requisitions.update');
        Route::get('{requisition}/destroy', 'RequisitionController@destroy')->name('requisitions.destroy');
       // Route::get('{requisition}/close', 'RequisitionController@close')->name('requisitions.close');
        Route::post('{requisition}/determine', 'RequisitionController@determine')->name('requisitions.determine');
        Route::post('/customizeReceiver', 'RequisitionController@customizeReceiver')->name('requisitions.customizeReceiver');
        Route::get('/ldapUsers', 'RequisitionController@ldapUsers')->name('requisitions.ldapUsers');
        Route::get('/staff', 'RequisitionController@staff')->name('requisitions.staff');
        Route::post('/draft', 'DraftsController@store')->name('draft.store');
        Route::get('/draft/{draft}/destroy', 'DraftsController@destroy')->name('draft.destroy');
        Route::get('/draft/{draft?}', 'DraftsController@list')->name('draft.list');

        Route::post('/viewers', 'RequisitionViewersController@store')->name('viewer.store');

    });
    Route::group(['prefix' => 'setting'], function () {
        Route::get('/', 'SettingsController@setting')->name('setting');
        Route::get('levels', 'SettingsController@levels')->name('setting.level');
    });


    /* Food reservation routes*/
    /*Route::group(['prefix' => 'foods', 'namespace' => 'FoodReservation', 'middleware' => 'checkUserRole' ] , function () {
        Route::get('/', 'FoodsController@index')->name('FoodReservation.foods.index');
        Route::post('/', 'FoodsController@store')->name('FoodReservation.foods.store');
        Route::get('/delete/{id}', 'FoodsController@delete')->name('FoodReservation.foods.delete');
        Route::get('/edit/{id}', 'FoodsController@edit')->name('FoodReservation.foods.edit');
        Route::post('/edit/{id}', 'FoodsController@update')->name('FoodReservation.foods.update');


    });
    Route::group(['prefix' => 'food-plan', 'namespace' => 'FoodReservation', 'middleware' => 'checkUserRole'], function () {
        Route::get('/{week_date}', 'FoodPlansController@index')->name('FoodReservation.foodPlans.index');
        Route::post('/store', 'FoodPlansController@store')->name('FoodReservation.foodPlans.store');
        Route::get('/delete/{firstDay}/{lastDay}', 'FoodPlansController@delete')->name('FoodReservation.foodPlans.delete');

    });
    Route::group(['prefix' => 'food-reserve', 'namespace' => 'FoodReservation'], function () {
        Route::get('/{week_date}', 'FoodsReserveController@index')->name('FoodReservation.FoodReserve.index');
        Route::post('/store', 'FoodsReserveController@store')->name('FoodReservation.FoodReserve.store');
    });
    Route::group(['prefix' => 'food-report', 'namespace' => 'FoodReservation', 'middleware' => 'checkUserRole'], function () {
        Route::get('/weekly/{week_date?}', 'FoodReportsController@weekly')->name('FoodReservation.food-report.weekly');
        Route::get('/daily/{day_date?}', 'FoodReportsController@daily')->name('FoodReservation.food-report.daily');
        Route::get('/createPDF', 'FoodReportsController@createPDF')->name('FoodReservation.food-report.createPDF');
    });*/

});

