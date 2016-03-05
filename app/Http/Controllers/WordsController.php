<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWordRequest;
use App\Http\Requests\UpdateWordRequest;

use App\Word;
use App\Wotd;
use App\Meaning;
use App\MeaningType;
use App\WordLanguage;

use DB;
use Log;
use Auth;
use File;
use Input;
use Session;
use Response;
use Debugbar;

class WordsController extends Controller {

	/**
	 * @var word
	 */
	private $word;

	/**
	 * Constructor
	 *
	 * @param Word $word
	 */
	public function __construct(Word $word)
	{
		$this->middleware('auth');

		$this->word = $word;
	}

	/**
	 * Show an individual word.
	 *
	 * @param Word $word
	 * @return View
	 */
	public function show($word)
	{
		$words[] = $word;
		return view('lists.words', compact('words'));
	}

	/**
	 * Show the edit page for a specific word.
	 *
	 * @param Word $word
	 * @return View
	 */
	public function edit($word)
	{
		$languages = WordLanguage::asKeyValuePairs();

		return view('words.edit', compact('word', 'languages'));
	}

	/**
	 * Update a word.
	 *
	 * @param CreateWordRequest $request
	 * @param Word $word
	 * @return mixed
	 */
	public function update(UpdateWordRequest $request, Word $word)
	{
		$word->update($request->except('created_at', 'updated_at'));

		Session::flash('success', "The word '".$word->text."' was updated.");
		return redirect()->route('word_edit_path', $word->id);
	}

	/**
	 * Show form for creating a new word.
	 *
	 * @return mixed
	 */
	public function create()
	{
		$languages = WordLanguage::asKeyValuePairs();
		$info_array['languages'] = $languages;

		if (Input::has('meaning_id'))
		{
			$meaning = Meaning::with('type')->with('words')->find(Input::get('meaning_id'));
			$info_array['meaning'] = $meaning;
		}

		if (Input::has('language_id'))
		{
			$info_array['language_id'] = Input::get('language_id');
		}

		return view('words.create', $info_array);
	}

	/**
	 * Store a new word.
	 *
	 * @param CreateWordRequest $request
	 * @return mixed
	 */
	public function store(CreateWordRequest $request)
	{
		// If this code is executed, validation has passed and we can create the word.
		$word = Word::create([
			'language_id' => $request->get('language_id'), 
			'text'        => $request->get('text'),
			'meaning_id'  => $request->get('meaning_id'),
			'comment'     => $request->get('comment'),
		]);

		Session::flash('success', "A new word '".$word->text."' was created.");
		return redirect()->route('meaning_edit_path', $word->meaning_id);
	}

	/**
	 * Delete a word
	 *
	 * @param Word $word
	 * @return mixed
	 */
	public function destroy(Word $word)
	{
		$user = Auth::user();

		$allowed_users = ['Daniel Eickhardt', 'Gabrielle Tranchet'];

		if (!in_array($user->name, $allowed_users))
		{
			Session::flash('error', "You don't have permission to do that.");
			return redirect()->back();
		}

		$oldword = $word->text;

		$word->delete();

		Session::flash('success', "The word '" .$oldword. "' was deleted.");
		return redirect()->route('search_path');
	}

	/**
	 * Search for a word.
	 *
	 * @param String $value  The value to search for.
	 * @return mixed
	 */
	public function search()
	{
		if (Input::has('search_term'))
		{
			$search_term = Input::get('search_term');
			$search_term = "%{$search_term}%";
			$words = Word::where('text', 'LIKE', $search_term)
				->orWhere('comment', 'LIKE', $search_term);

			if (Input::has('options') && Input::get('options'))
			{
				$options_obj = json_decode(Input::get('options'));

				if ($options_obj->types)
				{
					$words = $words->whereHas('meaning', function($q) use($options_obj)
					{
						$q->whereNotIn('meaning_type_id', $options_obj->types);
					});
				}
				
				if ($options_obj->languages)
				{
					$words = $words->whereNotIn('language_id', $options_obj->languages);
				}
			}

			$words = $words->orderBy('text', 'DESC')->get();

			return $words->toArray();
		}
		return false;
	}

	/**
	 * Show a random word.
	 *
	 * @return mixed
	 */
	public function random()
	{
		// dd(Word::random());
		$list_type = 'Random';
		$words[] = Word::random();
		$languages = WordLanguage::all();

		return view('lists.words', compact('words', 'list_type', 'languages'));
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
		$words = Word::onlyTrashed()->get();

		// dd($words);

		return view('lists.words', compact('words', 'list_type', 'languages'));
	}

	/**
	 * Restore a deleted word.
	 *
	 * @return mixed
	 */
	public function restore($id)
	{
		$word = Word::withTrashed()->find($id);
		$word->restore();

		Session::flash('success', "The word '" .$word->text. "' was restored.");
		return redirect()->route('words_trashed_path');
	}
}