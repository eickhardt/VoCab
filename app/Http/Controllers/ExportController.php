<?php

namespace App\Http\Controllers;

use App\CsvExport;
use App\Jobs\ProcessCsvExportJob;
use App\Port\CsvPortUtil;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Log;
use Session;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the export page.
     *
     * @return View
     */
    public function show()
    {
        return view('export.index');
    }

    /**
     * Check if the user is allowed to start an export and dispatch a job if so.
     *
     * @return RedirectResponse
     */
    public function export()
    {
        $user = Auth::user();

        if ($user->is_porting) {

            Session::flash('warning', 'Please wait for the current port to complete before starting a new one. You\'ll receive an email once its ready.');

        } else if ($user->meanings()->withTrashed()->count() < 1) {

            Session::flash('warning', 'You have no meanings to export. Go create some :)');

        } else {
            $user->is_porting = true;
            $user->save();

            ProcessCsvExportJob::dispatch($user, CsvPortUtil::getNextPortId());

            Session::flash(
                'success',
                'The export is being processed. You\'ll receive an email with a download link once its ready.'
            );
        }

        return redirect()->route('export_path');
    }

    /**
     * Serve the user's most recent export.
     *
     * @return RedirectResponse|BinaryFileResponse
     */
    public function download()
    {
        $user = Auth::user();

        // Get the most recent and available export
        $export = CsvExport::where('user_id', $user->id)
                           ->where('file_exists', 1)
                           ->where(
                               'updated_at',
                               '>',
                               Carbon::now()
                                     ->subHours(config('app.hours_to_keep_csv_export_files'))
                                     ->toDateTimeString()
                           )->orderBy('updated_at', 'desc')->first();

        if (!$export) {
            Session::flash('error', 'Export is expired or you haven\'t created one yet.');
            return redirect()->route('export_path');
        }

        $file_path = storage_path('app/' . CsvPortUtil::getCsvExportFilePath($export->file_name));

        $download_filename = CsvPortUtil::getCsvExportDownloadFileName();

        Log::info('User downloaded csv-export', [
            'export_db_id'      => $export->id,
            'user_id'           => $user->id,
            'download_filename' => $export->file_name
        ]);

        // Serve the generated file as a download, and then delete it
        return response()->download(
            $file_path,
            $download_filename,
            ['Content-Type' => 'text/csv']
        );
    }
}
