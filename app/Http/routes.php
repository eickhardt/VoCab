<?php

/**
 * Static guest routes
 */
$router->get('/', 
	['as' => 'home', 'uses' => 'HomeController@index']
);

// TODO: Fix proper registration
get('auth/register', function() {
	return redirect('/');
});

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
});

$router->bind('words', function($id) 
{
	return App\Word::with('language')->with('meaning')->find($id);
});

$router->resource('words', 'WordsController', [
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

$router->get('word/{id}/restore', 
	['as' => 'word_restore_path', 'uses' => 'WordsController@restore']
);

$router->get('word/random', 
	['as' => 'word_random_path', 'uses' => 'WordsController@random']
);

$router->get('word/trashed', 
	['as' => 'words_trashed_path', 'uses' => 'WordsController@showTrashed']
);

/**
 * Meanings resource
 */
$router->bind('meanings', function($id) 
{
	return App\Meaning::with('words')->with('type')->find($id);
});

$router->resource('meanings', 'MeaningsController', [
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

$router->get('meaning/{id}/restore', 
	['as' => 'meaning_restore_path', 'uses' => 'MeaningsController@restore']
);

$router->get('meaning/random', 
	['as' => 'meaning_random_path', 'uses' => 'MeaningsController@random']
);

$router->get('meaning/word_of_the_day', 
	['as' => 'meaning_wotd_path', 'uses' => 'MeaningsController@wotd']
);

$router->get('meaning/trashed', 
	['as' => 'meanings_trashed_path', 'uses' => 'MeaningsController@showTrashed']
);

/**
 * AJAX
 */
$router->post('ajax/simple_meaning', 
	['as' => 'ajax_simple_meaning_path', 'uses' => 'MeaningsController@getSimpleMeaning']
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

/**
 * Misc
 */
$router->get('user', 
	['as' => 'user_path', 'uses' => 'UsersController@show']
);

$router->get('user/settings', 
	['as' => 'user_settings_path', 'uses' => 'UsersController@settings']
);

$router->get('statistics', 
	['as' => 'statistics_path', 'uses' => 'StatisticsController@index']
);



$router->get('mwdata1', 
	['as' => 'mwdata1_path', 'uses' => 'BackupController@mwdata1']
);

$router->get('mwdata2', 
	['as' => 'mwdata2_path', 'uses' => 'BackupController@mwdata2']
);