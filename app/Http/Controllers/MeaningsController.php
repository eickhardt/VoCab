<?php namespace App\Http\Controllers;

use DB;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CreateMeaningRequest;
use App\Http\Requests\UpdateMeaningRequest;

use Illuminate\View\View;
use Auth;
use Input;
use Request;
use Session;

use App\Word;
use App\Wotd;
use App\Meaning;
use App\MeaningType;
use App\WordLanguage;

class MeaningsController extends Controller
{
    /**
     * @var Meaning meaning
     */
    private $meaning;

    /**
     * Constructor
     *
     * @param Meaning $meaning
     */
    public function __construct(Meaning $meaning)
    {
        $this->middleware('auth');

        $this->meaning = $meaning;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $languages = Auth::user()->activeLanguages;
        $types     = Meaning::all();

        return view('search.index', compact('languages', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $languages = Auth::user()->activeLanguages;
        $types     = MeaningType::asKeyValuePairs();

        return view('meanings.create', compact('languages', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateMeaningRequest $request
     * @return View
     * @throws AuthorizationException
     */
    public function store(CreateMeaningRequest $request)
    {
        $user = Auth::user();

        // If this code is executed, validation has passed and we can create the meaning
        $meaning                  = new Meaning;
        $meaning->meaning_type_id = $request->get('meaning_type_id');
        $meaning->user_id         = $user->id;

        // If the root language word is set, and the root isn't, we use the root language word as the root
        $root_language_short_name = $user->rootLanguage->short_name;
        if ($request->has($root_language_short_name) && !$request->get('root')) {
            $meaning->root = $request->get($root_language_short_name);
        } else {
            $meaning->root = $request->get('root');
        }
        $meaning->save();

        // We also want to create a word in each of the provided languages
        $languages      = Auth::user()->activeLanguages;
        $new_word_count = 0;
        foreach ($languages as $language) {
            if ($request->get($language->short_name)) {
                $word              = new Word;
                $word->text        = $request->get($language->short_name);
                $word->language_id = $language->id;
                $word->meaning_id  = $meaning->id;
                $word->user_id     = $user->id;
                $word->save();
                $new_word_count++;
            }
        }

        // Tell the user what happened and redirect
        Session::flash('success', "A new meaning '" . $meaning->root . "' was created, along with " . $new_word_count . " associated words.");

        return redirect()->route('meaning_edit_path', $meaning->id);
    }

    /**
     * Display the specified resource.
     *
     * @param Meaning $meaning
     * @return View
     * @throws AuthorizationException
     */
    public function show(Meaning $meaning): View
    {
        $this->authorize('view', $meaning);

        $meanings[] = $meaning;
        $languages  = Auth::user()->activeLanguages;

        return view('lists.meanings', compact('meanings', 'languages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Meaning $meaning
     * @return View
     * @throws AuthorizationException
     */
    public function edit(Meaning $meaning): View
    {
        $this->authorize('update', $meaning);

        $languages = Auth::user()->activeLanguages;
        $types     = MeaningType::asKeyValuePairs();
        $meaning   = Meaning::with('words')->find($meaning->id);

        return view('meanings.edit', compact('meaning', 'types', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMeaningRequest $request
     * @param Meaning $meaning
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UpdateMeaningRequest $request, Meaning $meaning): RedirectResponse
    {
        $this->authorize('update', $meaning);

        // Update the meaning, since validation and authorization has passed if we reach this code
        $meaning->meaning_type_id = $request->get('meaning_type_id');
        $meaning->root            = $request->get('root');
        $meaning->save();

        // Tell the user what happened and redirect
        Session::flash('success', "The meaning '" . $meaning->root . "' was updated.");
        return redirect()->route('meaning_edit_path', $meaning->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Meaning $meaning
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Meaning $meaning): RedirectResponse
    {
        // Authenticated, now soft delete the associated words
        $meaning->words()->delete();

        // Finally, delete the meaning itself
        $meaning_root = $meaning->root;
        $meaning->delete();

        // Let the user know what happened
        Session::flash('success', "The meaning '" . $meaning_root . "' was trashed, along with its associated words.");

        return redirect()->route('search_path');
    }

    /**
     * Show a random meaning.
     *
     * @return View|RedirectResponse
     */
    public function random()
    {
        $user = Auth::user();

        $meaning = Meaning::where('user_id', $user->id)
                          ->orderBy(DB::raw("RAND()"))
                          ->first();

        if (!$meaning) {
            Session::flash(
                'warning',
                'You don\' have any meanings yet :) Create some before you start using the random meaning button.');
            return redirect()->back();
        }

        $meanings[] = $meaning;

        $languages = $user->activeLanguages;
        $list_type = 'Random';

        return view('lists.meanings', compact('meanings', 'list_type', 'languages'));
    }

    /**
     * Display Word of the Day.
     *
     * @return View|RedirectResponse
     */
    public function showWotd()
    {
        $meanings[] = Wotd::getCurrent();

        if ($meanings[0] == null) {
            Session::flash(
                'warning',
                'You don\' have any words yet :) Create some to get a new "Word of the Day" every day.');
            return redirect()->back();
        }

        $languages = Auth::user()->activeLanguages;

        $list_type = 'Word of the Day';

        return view('lists.meanings', compact('meanings', 'list_type', 'languages'));
    }

    /**
     * Get simple information about a meaning (for AJAX)
     *
     * @param integer $meaning_id
     * @return array|string
     */
    public function getSimpleMeaning($meaning_id = null)
    {
        $html = false;
        if ($meaning_id) {
            $html = true;
        }

        $fail_array['root'] = 'No meaning found.';
        if (Input::has('meaning_id')) {
            $meaning_id = Input::get('meaning_id');
        }

        $meaning = Meaning::where('user_id', Auth::user()->id)
                          ->where('id', $meaning_id)
                          ->with(['words', 'words.language'])
                          ->first();

        if ($meaning) {
            if ($html) {
                return tipContent($meaning->words->sortBy('language_id'));
            }

            return $meaning;
        }
        return $fail_array;
    }

    /**
     * Show trashed words.
     *
     * @return mixed
     */
    public function showTrashed()
    {
        $list_type = 'Trashed';
        $languages = WordLanguage::all();
        $meanings  = Meaning::where('user_id', Auth::user()->id)->with('type')->onlyTrashed()->get();

        return view('lists.meanings', compact('meanings', 'list_type', 'languages'));
    }

    /**
     * Restore a deleted meaning.
     *
     * @param integer $id
     * @return RedirectResponse
     */
    public function restore(int $id): RedirectResponse
    {
        $meaning = Meaning::withTrashed()->find($id);
        $meaning->restore();

        $words = Word::withTrashed()->where('meaning_id', $meaning->id)->whereNull('deleted_at');
        $words->restore();

        Session::flash('success', "The meaning '" . $meaning->root . "' was restored, along with " . $words->count() . " associated words.");
        return redirect()->route('meanings_trashed_path');
    }
}

/**
 * Helper function to build HTML for translations tip on search page.
 * TODO: This should be done in JS!!
 *
 * @param $words
 * @return String
 */
function tipContent($words): string
{
    $result_array = [];
    foreach ($words as $word) {
        $result_array[$word['language']['name']][] = $word['text'] . '=' . $word['id'] . '=' . $word['comment'];
    }

    $html = '';
    foreach ($result_array as $name => $words) {
        $language = WordLanguage::where('name', $name)->first();

        $line               = '<p><img class="translation_image" alt="' . $language->name . '" src="' . $language->image . '">';
        $word_links_in_line = [];
        foreach ($words as $word) {
            $word_exploded = explode('=', $word);
            if ($word_exploded[2])
                $word_links_in_line[] = link_to_route('word_edit_path', $word_exploded[0], $word_exploded[1]) . ' - "<i>' . $word_exploded[2] . '</i>"';
            else
                $word_links_in_line[] = link_to_route('word_edit_path', $word_exploded[0], $word_exploded[1]);
        }
        // \Log::info($meaning_array['words']);
        $line .= implode(', ', $word_links_in_line);
        $line .= '</p>';
        $html = $html . $line;
    }

    return $html;
}
