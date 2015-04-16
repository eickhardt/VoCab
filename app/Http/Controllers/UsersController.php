<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Input;
use Session;

use App\WordLanguage;

class UsersController extends Controller {

	/**
	 * Displays a page where the user can edit their settings.
	 *
	 * @return View
	 */
	public function showSettings()
	{
		// Get the languages the user does NOT want displayed (this is an inverse relationship)
		$user_languages = Auth::user()->languages_id_array();

		// Get all languages
		$languages = WordLanguage::all();

		return view('users.settings.index', compact('languages', 'user_languages'));
	}

	/**
	 * Stores a users chosen settings.
	 *
	 * @return View
	 */
	public function storeSettings()
	{
		// Get the user that's logged in
		$user = Auth::user();

		// Grab all languages
		$languages = WordLanguage::all();

		// Check which languages the user wants dislpayed
		foreach ($languages as $language) 
		{
			// If the language is not is the post data, create (inverse) relationship
			if (!in_array($language->id, Input::all()))
			{
				if (!$user->languages()->find($language->id))
					$user->languages()->attach($language->id);
			}
			else
			{
				$user->languages()->detach($language->id);
			}
		}

		// dd($user_languages);

		Session::flash('success', 'You settings were updated.');
		return $this->showSettings();

		// return view('users.settings.index', compact('languages', 'user_languages'));
	}
}