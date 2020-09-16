<?php namespace App\Http\Controllers;

use App\Http\Requests\Request;
use Auth;
use Illuminate\View\View;
use Input;
use Log;
use Session;

use App\WordLanguage;

class UsersController extends Controller
{
    /**
     * Displays a page where the user can edit their settings.
     *
     * @return View
     */
    public function showSettings()
    {
        // Get the languages the user has enabled
        $user_languages = Auth::user()->languagesIdArray();

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
        // There must be at least one active language
        if (count(Input::except(['_token'])) < 1) {
            Session::flash('error', 'At least one active language is required.');
            return $this->showSettings();
        }

        // Get the user that's logged in
        $user = Auth::user();

        // Grab all languages
        $languages = WordLanguage::all();

        // Check which languages the user wants displayed
        foreach ($languages as $language) {
            // If the language is in the post data, create relationship
            if (in_array($language->id, Input::except(['_token']))) {
                if (!$user->languages()->find($language->id))
                    $user->languages()->attach($language->id);
            } else {
                $user->languages()->detach($language->id);
            }
        }

        Session::flash('success', 'Your settings were updated.');

        return $this->showSettings();
    }

    /**
     * Get currently authenticated user.
     *
     * @param Request $request
     * @return mixed
     */
    public function getAuthUser(Request $request)
    {
        return $request->user();
    }
}