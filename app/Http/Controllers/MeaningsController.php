<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests;
use App\Http\Requests\CreateMeaningRequest;
use App\Http\Requests\UpdateMeaningRequest;

use DB;
use Auth;
use Input;
use Session;

use App\Word;
use App\Wotd;
use App\Meaning;
use App\MeaningType;
use App\WordLanguage;

class MeaningsController extends Controller {

	/**
	 * @var word
	 */
	private $meaning;


	/**
	 * Constructor
	 *
	 * @param Word $word
	 */
	public function __construct(Meaning $meaning)
	{
		$this->middleware('auth');

		$this->meaning = $meaning;
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$languages = WordLanguage::all();
		$types = WordType::all();

		return view('search.index', compact('languages', 'types'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$languages = WordLanguage::all();
		$types = MeaningType::asKeyValuePairs();

		return view('meanings.create', compact('languages', 'types'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CreateMeaningRequest $request)
	{
		// If this code is executed, validation has passed and we can create the meaning.
		$meaning = new Meaning;
		$meaning->meaning_type_id = $request->get('meaning_type_id');
		$meaning->real_word_type = $request->get('real_word_type');
		$meaning->root = $request->get('en');
		$meaning->save();

		// We also want to create a word in each of the provided languages
		$languages = WordLanguage::all();
		$new_word_count = 0;
		foreach ($languages as $language) 
		{
			if ($request->get($language->short_name))
			{
				$word = new Word;
				$word->text = $request->get($language->short_name);
				$word->language_id = $language->id;
				$word->meaning_id = $meaning->id;
				$word->save();
				$new_word_count++;
			}
		}

		// Tell the user what happened and redirect
		Session::flash('success', "A new meaning '".$meaning->root."' was created, along with ".$new_word_count." associated words.");
		return redirect()->route('meaning_edit_path', $meaning->id);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($meaning)
	{
		$meanings[] = $meaning;
		$languages = WordLanguage::all();

		return view('lists.meanings', compact('meanings', 'languages'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Meaning $meaning)
	{
		$languages = WordLanguage::all();
		$types = MeaningType::asKeyValuePairs();
		$meaning = Meaning::with('words')->find($meaning->id);

		return view('meanings.edit', compact('meaning', 'types', 'languages'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(UpdateMeaningRequest $request, Meaning $meaning)
	{
		// Update the meaning, since validation has passed if we reach this code
		$meaning->real_word_type = $request->get('real_word_type');
		$meaning->meaning_type_id = $request->get('meaning_type_id');
		$meaning->root = $request->get('root');
		$meaning->save();

		// Tell the user what happened and redirect
		Session::flash('success', "The meaning '".$meaning->root."' was updated.");
		return redirect()->route('meaning_edit_path', $meaning->id);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Meaning $meaning)
	{
		// Authenticate or redirect
		$user = Auth::user();
		$allowed_users = ['Daniel Eickhardt', 'Gabrielle Tranchet'];
		if (!in_array($user->name, $allowed_users))
		{
			Session::flash('error', "You don't have permission to do that.");
			return redirect()->back();
		}

		// Authenticated, now soft delete the associated words
		$meaning->words()->delete();
		
		// Finally, delete the meaning itself
		$meaning_root = $meaning->root;
		$meaning->delete();

		// Let the user know what happened
		Session::flash('success', "The meaning '" .$meaning_root. "' was trashed, along with its associated words.");
		return redirect()->route('search_path');
	}


	/**
	 * Show a random word.
	 *
	 * @return mixed
	 */
	public function random()
	{
		$meanings[] = Meaning::random();
		$languages = WordLanguage::all();
		$list_type = 'Random';

		return view('lists.meanings', compact('meanings', 'list_type', 'languages'));
	}


	/**
	 * Display Word of the Day.
	 *
	 * @return mixed
	 */
	public function wotd()
	{
		$meanings[] = Wotd::getCurrent();
		$languages = WordLanguage::all();

		$list_type = 'Word of the Day';

		return view('lists.meanings', compact('meanings', 'list_type', 'languages'));
	}


	/**
	 * Get simple information about a meaning (for AJAX)
	 *
	 * @return Array
	 */
	public function getSimpleMeaning()
	{
		$fail_array['root'] = 'No meaning found.';
		if (Input::has('meaning_id'))
		{
			$meaning = Meaning::with('words')->find(Input::get('meaning_id'));
			if ($meaning)
			{
				$meaning_array = $meaning->toArray();
				// $meaning_array['words'] = $meaning->words->toArray();
				return $meaning_array;
				// return $meaning->toArray();
			}
			return $fail_array;
		}
		return $fail_array;
	}


	/**
	 * Show a random word.
	 *
	 * @return mixed
	 */
	public function showTrashed()
	{
		$list_type = 'Trashed';
		$languages = WordLanguage::all();
		$meanings = Meaning::with('type')->onlyTrashed()->get();

		return view('lists.meanings', compact('meanings', 'list_type', 'languages'));
	}


	/**
	 * Restore a deleted meaning.
	 *
	 * @return mixed
	 */
	public function restore($id)
	{
		$meaning = Meaning::withTrashed()->find($id);
		$meaning->restore();

		$words = Word::withTrashed()->where('meaning_id', $meaning->id);
		$restored_words_count = $words->count();
		$words->restore();

		Session::flash('success', "The meaning '" .$meaning->root. "' was restored, along with ".$words->count()." associated words.");
		return redirect()->route('meanings_trashed_path');
	}
}
