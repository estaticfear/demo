<?php

Route::group(['namespace' => 'Cmat\ReligiousMerit\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        // Settings
        Route::group(['prefix' => 'settings'], function () {
            Route::get('religious-merit', [
                'as' => 'settings.religious-merit',
                'uses' => 'ReligiousMeritController@getSettings',
                'permission' => 'religious-merit.settings',
            ]);
            Route::get('religious-merit-search-members', [
                'as' => 'religious-merit.search-members',
                'uses' => 'ReligiousMeritController@searchMembers',
                'permission' => 'religious-merit.settings',
            ]);
            Route::get('religious-merit-search-projects', [
                'as' => 'religious-merit.search-projects',
                'uses' => 'ReligiousMeritController@searchProjects',
                'permission' => 'religious-merit.settings',
            ]);
            Route::post('religious-merit', [
                'as' => 'settings.religious-merit-update',
                'permission' => 'religious-merit.settings',
                'uses' => 'ReligiousMeritController@postEditSettings',
            ]);
        });
        // Công đức
        Route::group(['prefix' => 'religious-merits', 'as' => 'religious-merit.'], function () {
            Route::resource('', 'ReligiousMeritController')->parameters(['' => 'religious-merit']);
            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'ReligiousMeritController@deletes',
                'permission' => 'religious-merit.destroy',
            ]);
            Route::get('get-detail/{merit_id}', [
                'as' => 'get-merit-detail',
                'uses' => 'ReligiousMeritController@getDetail',
                'permission' => 'religious-merit.edit',
            ]);
            Route::get('get-merit-products/{merit_id}/{product_type}', [
                'as' => 'get-merit-products',
                'uses' => 'ReligiousMeritController@getMeritProducts',
                'permission' => 'religious-merit-project.edit',
            ]);
            Route::post('update-merit-products/{merit_id}/{product_type}', [
                'as' => 'get-merit-products',
                'uses' => 'ReligiousMeritController@updateMeritProducts',
                'permission' => 'religious-merit-project.edit',
            ]);
        });
        // Dự án
        Route::group(['prefix' => 'religious-merit-projects', 'as' => 'religious-merit-project.'], function () {
            Route::resource('', 'ReligiousMeritProjectController')->parameters(['' => 'religious-merit-project']);
            Route::get('get-project-detail/{project_id}', [
                'as' => 'get-project-detail',
                'uses' => 'ReligiousMeritProjectController@getProjectDetail',
                'permission' => 'religious-merit-project.edit',
            ]);
            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'ReligiousMeritProjectController@deletes',
                'permission' => 'religious-merit-project.destroy',
            ]);
            Route::post('update-project-products', [
                'as' => 'update-project-products',
                'uses' => 'ReligiousMeritProjectController@updateProjectProducts',
                'permission' => 'religious-merit-project.edit',
            ]);
            Route::get('get-project-products/{project_id}/{product_type}', [
                'as' => 'get-project-products',
                'uses' => 'ReligiousMeritProjectController@getProjectProducts',
                'permission' => 'religious-merit-project.edit',
            ]);
        });
        // Danh mục dự án
        Route::group(['prefix' => 'religious-merit-project-categories', 'as' => 'religious-merit-project-category.'], function () {
            Route::resource('', 'ReligiousMeritProjectCategoryController')->parameters(['' => 'religious-merit-project-category']);
            Route::delete('items/destroy', [
                'as' => 'deletes',
                'uses' => 'ReligiousMeritProjectCategoryController@deletes',
                'permission' => 'religious-merit-project-category.destroy',
            ]);
        });
    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {

    }
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
        $projectPrefix = get_projects_prefix();
        $projectCategoryPrefix = get_projects_category_prefix();
        Route::get($projectPrefix . '/dang-trien-khai', [
            'as' => 'public.religious-merit-project.projects-available',
            'uses' => 'PublicController@getAvailableProjects',
        ]);

        Route::get($projectPrefix . '/da-ket-thuc', [
            'as' => 'public.religious-merit-project.projects-finished',
            'uses' => 'PublicController@getFinishedProjects',
        ]);

        Route::get($projectCategoryPrefix . '/{slug}', [
            'uses' => 'PublicController@getProjectsInCategory',
        ]);

        Route::get($projectPrefix . '/{slug}', [
            'uses' => 'PublicController@getAvailableProjectDetail',
        ]);

        Route::post('project/merit', [
            'as' => 'public.religious-merit.merit',
            'uses' => 'PublicController@merit',
        ]);

        Route::post('project/merit/upload-transaction-image', [
            'as' => 'public.religious-merit.upload-transaction-image',
            'uses' => 'PublicController@uploadTransactionImage',
        ]);

        Route::get('project/merits/{project_id}', [
            'as' => 'public.religious-merit-project.merits',
            'uses' => 'PublicController@getProjectMerits',
        ]);

        Route::get('project/budgets/{project_id}', [
            'as' => 'public.religious-merit-project.budgets',
            'uses' => 'PublicController@getProjectBudgets',
        ]);

        Route::get('project/efforts/{project_id}', [
            'as' => 'public.religious-merit-project.efforts',
            'uses' => 'PublicController@getProjectEfforts',
        ]);

        Route::get('project/artifacts/{project_id}', [
            'as' => 'public.religious-merit-project.artifacts',
            'uses' => 'PublicController@getProjectArtifacts',
        ]);

        Route::post('project/merits/report/{project_id}', [
            'as' => 'public.religious-merit-project.merits.report',
            'uses' => 'PublicController@getProjectReport',
        ]);

        Route::get('project/merits/export-project-report/{project_id}', [
            'as' => 'public.religious-merit-project.export-project-report',
            'uses' => 'PublicController@exportProjectReport',
        ]);
    });

});

