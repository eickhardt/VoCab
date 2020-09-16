<?php namespace App\Http\Controllers;

use App\Http\Requests\CreateWordRequest;
use App\Http\Requests\UpdateWordRequest;

use App\Word;
use App\Meaning;
use App\WordLanguage;

use DB;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Auth;
use Input;
use Session;

class WordsController extends Controller
{
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
     * @throws AuthorizationException
     */
    public function show(Word $word)
    {
        $this->authorize('view', $word);

        $words[] = $word;
        return view('lists.words', compact('words'));
    }

    /**
     * Show the edit page for a specific word.
     *
     * @param Word $word
     * @return View
     * @throws AuthorizationException
     */
    public function edit(Word $word)
    {
        $this->authorize('update', $word);

        $languages = WordLanguage::asKeyValuePairs();

        return view('words.edit', compact('word', 'languages'));
    }

    /**
     * Update a word.
     *
     * @param UpdateWordRequest $request
     * @param Word $word
     * @return mixed
     * @throws AuthorizationException
     */
    public function update(UpdateWordRequest $request, Word $word)
    {
        $this->authorize('update', $word);

        $meaning = Meaning::find($request->meaning_id);
        if (!$meaning || $meaning->user_id != Auth::user()->id) {
            Session::flash('error', "The selected meaning id is invalid.");
        } else {
            $word->update($request->except('created_at', 'updated_at'));
            Session::flash('success', "The word '" . $word->text . "' was updated.");
        }

        return redirect()->route('word_edit_path', $word->id);
    }

    /**
     * Show form for creating a new word.
     *
     * @return View
     */
    public function create()
    {
        $info_array['languages'] = Auth::user()->languages()->pluck('name', 'id');

        if (Input::has('meaning_id')) {
            $meaning = Meaning::with('type')->with('words')->find(Input::get('meaning_id'));
            $info_array['meaning'] = $meaning;
        }

        if (Input::has('language_id')) {
            $info_array['language_id'] = Input::get('language_id');
        }

        return view('words.create', $info_array);
    }

    /**
     * Store a new word.
     *
     * @param CreateWordRequest $request
     * @return RedirectResponse
     */
    public function store(CreateWordRequest $request)
    {
        // If this code is executed, validation has passed and we can create the word.
        $word = Word::create([
            'language_id' => $request->get('language_id'),
            'text' => $request->get('text'),
            'meaning_id' => $request->get('meaning_id'),
            'comment' => $request->get('comment'),
            'user_id' => Auth::user()->id,
        ]);

        Session::flash('success', "A new word '" . $word->text . "' was created.");
        return redirect()->route('meaning_edit_path', $word->meaning_id);
    }

    /**
     * Soft delete a word.
     *
     * @param Word $word
     * @return mixed
     * @throws Exception
     */
    public function destroy(Word $word)
    {
        $old_word = $word->text;

        $word->delete();

        Session::flash('success', "The word '" . $old_word . "' was deleted.");
        return redirect()->route('search_path');
    }

    /**
     * Search for a word.
     *
     * @return boolean|array
     */
    public function search()
    {
        if (Input::has('search_term')) {
            $search_term = Input::get('search_term');
            $search_term = "%{$search_term}%";
            $words = Word::query()
                ->where('user_id', Auth::user()->id)
                ->where('text', 'LIKE', $search_term)
                ->orWhere('comment', 'LIKE', $search_term);

            if (Input::has('options') && Input::get('options')) {
                $options_obj = json_decode(Input::get('options'));

                if ($options_obj->types) {
                    $words = $words->whereHas('meaning', function ($q) use ($options_obj) {
                        $q->whereNotIn('meaning_type_id', $options_obj->types);
                    });
                }

                if ($options_obj->languages) {
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
     * @return View|RedirectResponse
     */
    public function random()
    {
        $word = Word::where('user_id', Auth::user()->id)
            ->orderBy(DB::raw("RAND()"))
            ->first();

        if (!$word) {
            Session::flash(
                'error',
                'You don\' have any words yet :) Create some before you start using the random word button.');
            return redirect()->back();
        }
        $words[] = $word;

        $list_type = 'Random';
        $languages = WordLanguage::all();

        return view('lists.words', compact('words', 'list_type', 'languages'));
    }

    /**
     * Show a random word.
     *
     * @return View
     */
    public function showTrashed()
    {
        $list_type = 'Trashed';
        $languages = WordLanguage::all();
        $words = Word::onlyTrashed()->where('user_id', Auth::user()->id)->get();

        return view('lists.words', compact('words', 'list_type', 'languages'));
    }

    /**
     * Restore a deleted word. Authorization is done in WordPolicy.
     *
     * @param integer $id
     * @return RedirectResponse
     */
    public function restore($id)
    {
        $word = Word::withTrashed()->find($id);

        if (Meaning::find($word->meaning_id) == null) {
            Session::flash('success', "The word '" . $word->text . "' was restored. The meaning it was attached to no longer exists though.");
        } else {
            Session::flash('success', "The word '" . $word->text . "' was restored.");
        }

        $word->restore();

        return redirect()->route('words_trashed_path');
    }

    /**
     * Show all words paginated ordered by the time of creation.
     *
     * @return View
     */
    public function showMostRecent()
    {
        $list_type = 'Recent';
        $languages = WordLanguage::all();
        $words = Auth::user()->words()->paginate(50);

        return view('lists.words', compact('words', 'list_type', 'languages'));
    }
}