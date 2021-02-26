<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Jobs\ProcessCsvImportJob;
use App\Meaning;
use App\Port\Import\CsvColumnHeaderValidator;
use App\Port\Import\CsvImporter;
use App\Port\CsvPortUtil;
use App\Word;
use Auth;
use Illuminate\Http\RedirectResponse;
use App\WordLanguage;
use Illuminate\View\View;
use Session;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the import page.
     *
     * @return View
     */
    public function show(): View
    {
        $user_id = Auth::user()->id;

        // All languages for the language selector
        $languages      = WordLanguage::all(['id', 'name'])->pluck('name', 'id');
        $words_count    = Word::withTrashed()->where('user_id', $user_id)->count();
        $meanings_count = Meaning::withTrashed()->where('user_id', $user_id)->count();

        return view('import.index', compact('languages', 'words_count', 'meanings_count'));
    }

    /**
     * Import the given CSV file to database.
     *
     * @param ImportRequest $request Validation happens here.
     * @return RedirectResponse
     */
    public function import(ImportRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->isPortLocked()) {
            Session::flash('warning', 'Please wait for the current port to complete processing before ' .
                                      'initiating a new one. You\'ll receive an email once its ready.');
            return redirect()->route('import_path');
        }

        $user->lockPorting();

        $importer = new CsvImporter(
            new CsvColumnHeaderValidator(),
            $user,
            $request->file('csv_file')->getRealPath(),
            $request->fingerprint()
        );

        ProcessCsvImportJob::dispatch(
            $user,
            $importer,
            $request->fingerprint()
        );

        Session::flash(
            'success',
            'The import is being processed. You\'ll receive an email when its ready.'
        );

        return redirect()->route('import_path');
    }
}
