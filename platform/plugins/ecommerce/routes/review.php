<?php

use Cmat\Ecommerce\Models\Product;

Route::group(['namespace' => 'Cmat\Ecommerce\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
            Route::resource('', 'ReviewController')->parameters(['' => 'review'])->only(['index', 'destroy']);

            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'ReviewController@deletes',
                'permission' => 'reviews.destroy',
            ]);
        });
    });
});

Route::group([
    'namespace' => 'Cmat\Ecommerce\Http\Controllers\Fronts',
    'middleware' => ['web', 'core', 'customer'],
], function () {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        Route::post('review/create', [
            'as' => 'public.reviews.create',
            'uses' => 'ReviewController@store',
        ]);

        Route::delete('review/delete/{id}', [
            'as' => 'public.reviews.destroy',
            'uses' => 'ReviewController@destroy',
        ])->where('id', BaseHelper::routeIdRegex());

        Route::get(SlugHelper::getPrefix(Product::class, 'products') . '/{slug}/review', [
            'uses' => 'ReviewController@getProductReview',
            'as' => 'public.product.review',
            'middleware' => 'customer',
        ]);
    });
});
