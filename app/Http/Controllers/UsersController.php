<?php namespace App\Http\Controllers;

use App\Http\Requests\DeleteAllRequest;
use App\Http\Requests\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Input;
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
        $user = auth()->user();

        // Get the languages the user has enabled
        $user_languages = $user->languagesIdArray();

        // Get all languages
        $languages = WordLanguage::all();
        $selector_languages = $languages->pluck('name', 'id');

        // Get the user's root language
        $root_language_id = $user->rootLanguage()->pluck('id');

        return view('users.settings.index',
            compact('languages', 'selector_languages', 'user_languages', 'root_language_id'));
    }

    /**
     * Stores a users active languages settings.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeActiveLanguageSettings()
    {
        // There must be at least one active language
        if (count(Input::except(['_token'])) < 1) {
            Session::flash('error', 'At least one active language is required.');
            redirect()->route('user_settings_path');
        } else if (count(Input::except(['_token'])) > config('app.max_active_languages')) {
            Session::flash('error', 'A maximum of 12 active languages is currently allowed.');
            redirect()->route('user_settings_path');
        }

        // Get the user that's logged in
        $user = auth()->user();

        // Grab all languages
        $languages = WordLanguage::all();

        // Verify that the root language is among the selected ones
        $root_language_verified = false;
        foreach ($languages as $language) {
            if ($language->id == $user->root_language_id) {
                $root_language_verified = true;
            }
        }

        if (!$root_language_verified) {
            Session::flash('error', 'The language which is currently selected as root can\'t be disabled.');
            redirect()->route('user_settings_path');
        }

        // Check which languages the user wants displayed
        foreach ($languages as $language) {
            // If the language is in the post data, create relationship
            if (in_array($language->id, Input::except(['_token']))) {
                if (!$user->languages()->find($language->id)) {
                    $user->languages()->attach($language->id);
                }
            } else {
                // Otherwise, remove the relationship
                $user->languages()->detach($language->id);
            }
        }

        Session::flash('success', 'The active languages settings were updated.');

        return redirect()->route('user_settings_path');
    }

    /**
     * Stores a users root language setting.
     *
     * @return RedirectResponse
     */
    public function storeRootLanguageSettings()
    {
        // Get the user that's logged in
        $user = auth()->user();

        // Check that the language the user has selected as root is actually active
        $new_root_language_id = Input::get('root_language_id');
        $language_is_active = false;

        foreach ($user->languages as $language) {
            if ($new_root_language_id == $language->id) {
                $language_is_active = true;
            }
        }

        // If the language is active, save the new root language
        if ($language_is_active) {
            $user->root_language_id = Input::get('root_language_id');
            $user->save();

            Session::flash('success', 'The root language setting was updated.');
        } else {
            Session::flash('error', 'The selected root language must be active.');
        }

        return redirect()->route('settings');
    }

    /**
     * Get currently authenticated user.
     *
     * @return mixed
     */
    public function getAuthUser()
    {
        return auth()->user();
    }

    /**
     * Deletes all words and meanings this user has associated. Meant to be used before an import.
     *
     * @param DeleteAllRequest $request
     * @return RedirectResponse
     */
    public function deleteAll(DeleteAllRequest $request)
    {
        $user = $request->user();

        $user->wotds()->forceDelete();
        $user->words()->forceDelete();
        $user->meanings()->forceDelete();

        Session::flash('success', 'Data cleared. Ready for fresh import.');
        return redirect()->route('import_path');
    }
}