<?php

Route::group(['namespace' => 'Cmat\Vnpay\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'settings'], function () {
            Route::get('vnpay', [
                'as' => 'settings.vnpay',
                'uses' => 'VnpayController@getVnpaySetting',
                'permission' => 'vnpay.settings',
            ]);
            Route::post('vnpay', [
                'as' => 'settings.vnpay-setting',
                'permission' => 'vnpay.settings',
                'uses' => 'VnpayController@postEditSettings',
            ]);
        });

        Route::group(['prefix' => 'vnpays', 'as' => 'vnpay.'], function () {
            Route::resource('', 'VnpayController')->parameters(['' => 'vnpay']);
            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'VnpayController@deletes',
                'permission' => 'vnpay.destroy',
            ]);
            Route::post('items/resync/{id}', [
                'as' => 'resync',
                'uses' => 'VnpayController@resync',
                'permission' => 'vnpay.resync',
            ])->where('id', BaseHelper::routeIdRegex());
        });
    });

    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::get('vnpay/testpay', [
            'as' => 'public.vnpay.test',
            'uses' => 'PublicController@testPayment',
        ]);
        Route::get('vnpay/verify-result', [
            'as' => 'public.vnpay.verify-result',
            'uses' => 'PublicController@verifyResult',
        ]);
        Route::get('vnpay/ipn', [
            'as' => 'public.vnpay.ipn',
            'uses' => 'PublicController@ipn',
        ]);
    });

});
