<?php

namespace App\Http\Middleware;

use App\Meaning;
use App\Word;
use Auth;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Session;

class FirstLogin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Closure|RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user->is_first_login) {
            $user->is_first_login = false;
            $user->save();

            // Create the users first meaning
            $meaning = new Meaning;
            $meaning->meaning_type_id = 2; // Noun
            $meaning->root = 'dog';
            $meaning->user_id = $user->id;
            $meaning->save();

            // Create the users first word
            $word = new Word;
            $word->text = 'dog';
            $word->language_id = 1; // English
            $word->meaning_id = $meaning->id;
            $word->user_id = $user->id;
            $word->save();

            // Assign english as the only default active language
            $user->languages()->attach(1); // English

            // First time the user logs in we want them to set active languages on the settings page
            Session::flash('success', "Welcome to Vokapp :) Please select the languages you'd like to work with.");
            return redirect()->route('user_settings_path');
        }

        return $next($request);
    }
}
