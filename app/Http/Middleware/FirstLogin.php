<?php

namespace App\Http\Middleware;

use App\Meaning;
use App\MeaningType;
use App\Word;
use App\WordLanguage;
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
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->is_first_login) {
            $user->is_first_login = false;
            $user->save();

            // Create the users first meaning
            $meaning                  = new Meaning;
            $meaning->meaning_type_id = MeaningType::whereName('noun')->first()->id;
            $meaning->root            = 'dog';
            $meaning->user_id         = $user->id;
            $meaning->save();

            $language_id = WordLanguage::whereShortName('en')->first()->id;

            // Create the users first word
            $word              = new Word;
            $word->text        = 'dog';
            $word->language_id = $language_id;
            $word->meaning_id  = $meaning->id;
            $word->user_id     = $user->id;
            $word->save();

            // Assign english as the only default active language
            $user->activeLanguages()->attach($language_id);

            // First time the user logs in we want them to set active / root languages on the settings page
            Session::flash('success', "Welcome to Vokapp :) Please select your root language and the languages you'd like to learn.");

            return redirect()->route('user_settings_path');
        }

        return $next($request);
    }
}
