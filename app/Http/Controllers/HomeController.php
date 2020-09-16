<?php namespace App\Http\Controllers;

use App\WordLanguage;
use App\MeaningType;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Input;
use Auth;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return View
     */
    public function index()
    {
        if (Auth::guest())
            return view('home');
        else
            return $this->showSearch();
    }

    /**
     * Show the search page.
     *
     * @return View|RedirectResponse
     */
    public function showSearch()
    {
        // Get languages and meaning types to display in the search options
        $languages = Auth::user()->languages;
        $types = MeaningType::all();

        // Get the languages that are enabled for this user
        $user_languages = Auth::user()->languagesIdArray();

        // Get search term that may have been set
        $s = '';
        if (Input::has('s')) {
            $s = Input::get('s');
        }

        return view('search.index', compact('languages', 'user_languages', 'types', 's'));
    }

    /**
     * Show the search page with a search term already input.
     *
     * @return RedirectResponse
     */
    public function showSpecificSearch()
    {
        $query = ['s' => Input::get('s')];
        return redirect()->route('search_path', $query);
    }
}