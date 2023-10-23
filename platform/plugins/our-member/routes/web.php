<?php

Route::group(['namespace' => 'Cmat\OurMember\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'our-members', 'as' => 'our-member.'], function () {
            Route::resource('', 'OurMemberController')->parameters(['' => 'our-member']);
            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'OurMemberController@deletes',
                'permission' => 'our-member.destroy',
            ]);
        });
    });

});
