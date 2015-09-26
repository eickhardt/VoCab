<?php

// Move this!
$monolog = Log::getMonolog();
$syslog = new \Monolog\Handler\SyslogHandler('papertrail');
$formatter = new \Monolog\Formatter\LineFormatter('%channel%.%level_name%: %message% %extra%');
$syslog->setFormatter($formatter);

$monolog->pushHandler($syslog);

/**
 * Static guest routes
 */
$router->get('/', 
	['as' => 'home', 'uses' => 'HomeController@index']
);

Route::controllers([
	'auth' => 'Auth\AuthController',
 	'password' => 'Auth\PasswordController',
]);


/**
 * Routes that require login
 */
Route::group(['middleware' => 'auth'], function()
{
	Route::get('search', 
		['as' => 'search_path', 'uses' => 'HomeController@showSearch']
	);
	Route::post('search', 
		['as' => 'search_bar_path', 'uses' => 'HomeController@showSpecificSearch']
	);
	Route::get('settings', 
		['as' => 'user_settings_path', 'uses' => 'UsersController@showSettings']
	);
	Route::post('settings',
		['as' => 'settings_store_path', 'uses' => 'UsersController@storeSettings']
	);
	Route::get('tests/languages', 
		['as' => 'languages_test_path', 'uses' => 'TestsController@languages']
	);
});

$router->bind('translations', function($id) 
{
	return App\Word::with('language')->with('meaning')->find($id);
});

$router->resource('translations', 'WordsController', [
	'names' => [
		'index' => 'words_path',
		'show' => 'word_path',
		'destroy' => 'word_delete_path',
		'create' => 'word_create_path',
		'update' => 'word_update_path',
		'edit' => 'word_edit_path',
		'store' => 'word_store_path',
	]
]);

$router->get('translation/{id}/restore', 
	['as' => 'word_restore_path', 'uses' => 'WordsController@restore']
);

$router->get('translation/random', 
	['as' => 'word_random_path', 'uses' => 'WordsController@random']
);

$router->get('translation/trashed', 
	['as' => 'words_trashed_path', 'uses' => 'WordsController@showTrashed']
);

/**
 * Meanings resource
 */
$router->bind('words', function($id) 
{
	return App\Meaning::with('words')->with('type')->find($id);
});

$router->resource('words', 'MeaningsController', [
	'names' => [
		'index' => 'meanings_path',
		'show' => 'meaning_path',
		'destroy' => 'meaning_delete_path',
		'create' => 'meaning_create_path',
		'update' => 'meaning_update_path',
		'edit' => 'meaning_edit_path',
		'store' => 'meaning_store_path',
	]
]);

$router->get('word/{id}/restore', 
	['as' => 'meaning_restore_path', 'uses' => 'MeaningsController@restore']
);

$router->get('word/random', 
	['as' => 'meaning_random_path', 'uses' => 'MeaningsController@random']
);

$router->get('word/word_of_the_day', 
	['as' => 'meaning_wotd_path', 'uses' => 'MeaningsController@wotd']
);

$router->get('word/trashed', 
	['as' => 'meanings_trashed_path', 'uses' => 'MeaningsController@showTrashed']
);

/**
 * AJAX
 */
$router->post('ajax/simple_meaning', 
	['as' => 'ajax_simple_meaning_path', 'uses' => 'MeaningsController@getSimpleMeaning']
);

$router->get('ajax/simple_meaning/{meaning_id}', 
	['as' => 'ajax_get_simple_meaning_path', 'uses' => 'MeaningsController@getSimpleMeaning']
);

$router->post('ajax/words_search', 
	['as' => 'ajax_word_search_path', 'uses' => 'WordsController@search']
);

/**
 * Backup
 */
$router->get('backup/download/{id}', 
	['as' => 'download_backup_path', 'uses' => 'BackupController@download']
);

$router->delete('backup/delete/{id}', 
	['as' => 'backup_delete_path', 'uses' => 'BackupController@destroy']
);

$router->get('backup/do', 
	['as' => 'backup_path', 'uses' => 'BackupController@backup']
);

$router->get('backup', 
	['as' => 'backup_show_path', 'uses' => 'BackupController@show']
);

$router->get('mwdata1', 
	['as' => 'mwdata1_path', 'uses' => 'BackupController@mwdata1']
);

$router->get('mwdata2', 
	['as' => 'mwdata2_path', 'uses' => 'BackupController@mwdata2']
);

/**
 * Misc
 */
$router->get('statistics', 
	['as' => 'statistics_path', 'uses' => 'StatisticsController@index']
);

$router->get('statistics_2', 
	['as' => 'statistics_path2', 'uses' => 'StatsFileController@index']
);

/**
 * Tests
 */
// $router->get('tests/mail', 
// 	['as' => 'test_mail_path', 'uses' => 'TestsController@mail']
// );