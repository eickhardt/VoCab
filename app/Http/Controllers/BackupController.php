<?php namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Response;
use Session;
use Artisan;
use Auth;

use App\Backup;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Backup the database.
     *
     * @return RedirectResponse
     */
    public function backup(): RedirectResponse
    {
        // Check if the user has permission to do this
        $user = Auth::user();
        if (!$user->is_admin) {
            Session::flash('error', "You don't have permission to do that.");
            return redirect()->route('backup_show_path');
        }

        // Create the backup
        Artisan::call('backup:run', ['--only-db' => true]);

        // Create a corresponding database row
        $backup_path = storage_path() . '/app/' . config('app.name');
        $files       = scandir($backup_path, SCANDIR_SORT_DESCENDING);
        $newest_file = $files[0];
        Backup::create(['user_id' => $user->id, 'file' => $newest_file]);

        // Redirect back with a message to the user
        Session::flash('success', "A new snapshot has been created.");
        return redirect()->route('backup_show_path');
    }

    /**
     * Show the backup page.
     *
     * @return View
     */
    public function show(): View
    {
        $backups = Backup::with('user')->orderBy('created_at', 'DESC')->take(10)->get();

        return view('backup.index', compact('backups'));
    }

    /**
     * Serve download of specific backup snapshot.
     *
     * @param $id integer
     * @return BinaryFileResponse
     */
    public function download(int $id): BinaryFileResponse
    {
        $backup = Backup::find($id);

        return Response::download(storage_path() . '/app/' . config('app.name') . $backup->file, $backup->file, []);
    }

    /**
     * Serve download of specific backup snapshot.
     *
     * @param $id integer
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(int $id): RedirectResponse
    {
        // Check if the user has permission to do this
        if (!Auth::user()->is_admin) {
            Session::flash('error', "You don't have permission to do that.");
            return redirect()->route('backup_show_path');
        }

        $backup = Backup::find($id);

        unlink(storage_path() . '/app/' . config('app.name') . '/' . $backup->file);

        $backup_name = $backup->file;
        $backup->delete();

        Session::flash('success', "The snapshot '" . $backup_name . "' was deleted.");
        return redirect()->route('backup_show_path');
    }
}
