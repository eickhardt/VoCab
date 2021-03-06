<?php

/**
 * Static guest routes
 */
Route::get('/',
           ['as' => 'home', 'uses' => 'HomeController@index']
);

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');

Route::group(['middleware' => ['web']], function () {
    Route::group(['middleware' => ['bindings']], function () {
        /**
         * Routes that require login
         */
        Route::group(['middleware' => ['auth', 'firstlogin']], function () {
            Route::get('search',
                       ['as' => 'search_path', 'uses' => 'HomeController@showSearch']
            );
            Route::post('search',
                        ['as' => 'search_bar_path', 'uses' => 'HomeController@showSpecificSearch']
            );
            Route::get('settings',
                       ['as' => 'user_settings_path', 'uses' => 'UsersController@showSettings']
            );
            Route::post('settings-active-languages',
                        ['as' => 'settings_store_active_languages_path', 'uses' => 'UsersController@storeActiveLanguageSettings']
            );
            Route::post('settings-root-language',
                        ['as' => 'settings_store_root_language_path', 'uses' => 'UsersController@storeRootLanguageSettings']
            );
            Route::get('tests/languages',
                       ['as' => 'languages_test_path', 'uses' => 'TestsController@languages']
            );

            Route::get('word/{id}/restore',
                       ['as' => 'word_restore_path', 'uses' => 'WordsController@restore']
            );

            Route::get('word/random',
                       ['as' => 'word_random_path', 'uses' => 'WordsController@random']
            );

            Route::get('word/trashed',
                       ['as' => 'words_trashed_path', 'uses' => 'WordsController@showTrashed']
            );

            Route::get('words/recent',
                       ['as' => 'recent_words_path', 'uses' => 'WordsController@showMostRecent']
            );

            Route::bind('word', function ($id) {
                return App\Word::with('language')->with('meaning')->find($id);
            });

            Route::resource('word', 'WordsController', [
                'names' => [
                    'index'   => 'words_path',
                    'show'    => 'word_path',
                    'destroy' => 'word_delete_path',
                    'create'  => 'word_create_path',
                    'update'  => 'word_update_path',
                    'edit'    => 'word_edit_path',
                    'store'   => 'word_store_path',
                ]
            ]);


            Route::get('meaning/{id}/restore',
                       ['as' => 'meaning_restore_path', 'uses' => 'MeaningsController@restore']
            );

            Route::get('meaning/random',
                       ['as' => 'meaning_random_path', 'uses' => 'MeaningsController@random']
            );

            Route::get('meaning/word_of_the_day',
                       ['as' => 'meaning_wotd_path', 'uses' => 'MeaningsController@showWotd']
            );

            Route::get('meaning/trashed',
                       ['as' => 'meanings_trashed_path', 'uses' => 'MeaningsController@showTrashed']
            );
            /**
             * Meanings resource
             */
            Route::bind('meaning', function ($id) {
                return App\Meaning::with('words')->with('type')->find($id);
            });

            Route::resource('meaning', 'MeaningsController', [
                'names' => [
                    'index'   => 'meanings_path',
                    'show'    => 'meaning_path',
                    'destroy' => 'meaning_delete_path',
                    'create'  => 'meaning_create_path',
                    'update'  => 'meaning_update_path',
                    'edit'    => 'meaning_edit_path',
                    'store'   => 'meaning_store_path',
                ]
            ]);

            /**
             * Import
             */
            Route::get('import',
                       ['as' => 'import_path', 'uses' => 'ImportController@show']
            );
            Route::post('import',
                        ['as' => 'import_do_path', 'uses' => 'ImportController@import']
            );

            /**
             * Export
             */
            Route::get('export',
                       ['as' => 'export_path', 'uses' => 'ExportController@show']
            );
            Route::post('export',
                        ['as' => 'export_do_path', 'uses' => 'ExportController@export']
            );
            Route::get('export/download',
                       ['as' => 'csv_export_download_path', 'uses' => 'ExportController@download']
            );
            Route::post('delete_all',
                        ['as' => 'delete_all_path', 'uses' => 'UsersController@deleteAll']
            );

            /**
             * AJAX
             */
            Route::post('ajax/simple_meaning',
                        ['as' => 'ajax_simple_meaning_path', 'uses' => 'MeaningsController@getSimpleMeaning']
            );
            Route::get('ajax/simple_meaning/{meaning_id}',
                       ['as' => 'ajax_get_simple_meaning_path', 'uses' => 'MeaningsController@getSimpleMeaning']
            );

            Route::post('ajax/words_search',
                        ['as' => 'ajax_word_search_path', 'uses' => 'WordsController@search']
            );

            /**
             * Backup
             */
            Route::get('backup/download/{id}',
                       ['as' => 'download_backup_path', 'uses' => 'BackupController@download']
            );

            Route::delete('backup/delete/{id}',
                          ['as' => 'backup_delete_path', 'uses' => 'BackupController@destroy']
            );

            Route::get('backup/do',
                       ['as' => 'backup_path', 'uses' => 'BackupController@backup']
            );

            Route::get('backup',
                       ['as' => 'backup_show_path', 'uses' => 'BackupController@show']
            );

            Route::get('mwdata1',
                       ['as' => 'mwdata1_path', 'uses' => 'BackupController@mwdata1']
            );

            Route::get('mwdata2',
                       ['as' => 'mwdata2_path', 'uses' => 'BackupController@mwdata2']
            );

            /**
             * Statistics
             */
            Route::get('statistics',
                       ['as' => 'statistics_path', 'uses' => 'StatisticsController@index']
            );

            Route::get('contribution-calendar-data/{year}',
                       ['as' => 'contribution_calendar_data', 'uses' => 'StatisticsController@getContributionCalendarData']
            );
            Route::get('contribution-calendar-years',
                       ['as' => 'contribution_calendar_years', 'uses' => 'StatisticsController@getYearsForContributionCalendars']
            );
        });

        /**
         * Tests
         */
//        Route::get('tests/mail',
//            ['as' => 'test_mail_path', 'uses' => 'TestsController@mail']
//        );
    });
});