<?php namespace App\Http\Controllers;

use App\Word;
use App\WordLanguage;
use App\MeaningType;
use App\Wotd;

use Input;
use Auth;
// use Redirect;

class HomeController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// $this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index(Word $word)
	{
		$wordcount = $word->count();
		$wotd = Wotd::getCurrent();

		if (Auth::guest())
			return view('home', compact('wordcount', 'wotd'));
		else
			return $this->showSearch();
	}

	/**
	 * Show the search page.
	 *
	 * @param Word $word
	 * @return View
	 */
	public function showSearch()
	{
		$languages = WordLanguage::all();
		$info_array['languages'] = $languages;

		// Get the languages the user does NOT want displayed (this is an inverse relationship)
		$user_languages = Auth::user()->languages_id_array();
		$info_array['user_languages'] = $user_languages;

		$types = MeaningType::all();
		$info_array['types'] = $types;

		if (Input::has('s'))
		{
			$info_array['s'] = Input::get('s');
		}

		return view('search.index', $info_array);
	}
}