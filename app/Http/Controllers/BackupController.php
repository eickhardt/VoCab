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
     * @return mixed
     */
    public function backup()
    {
        // Check if the user has permission to do this
        $user = Auth::user();
        $allowed_users = ['Daniel Eickhardt', 'Gabrielle Tranchet'];
        if (!in_array($user->name, $allowed_users)) {
            Session::flash('error', "You don't have permission to do that.");
            return redirect()->back();
        }

        // Create the backup
        Artisan::call('backup:run', ['--only-db' => true]);

        // Create a corresponding database row
        $backup_path = storage_path() . '/app/Vocab';
        $files = scandir($backup_path, SCANDIR_SORT_DESCENDING);
        $newest_file = $files[0];
        Backup::create(['user_id' => $user->id, 'file' => $newest_file]);

        // Redirect back with a message to the user
        Session::flash('success', "A new snapshot has been created.");
        return $this->show();
    }

    /**
     * Show the backup page.
     *
     * @return View
     */
    public function show()
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
    public function download($id)
    {
        $backup = Backup::find($id);
        return Response::download(storage_path() . '/app/Vocab/' . $backup->file, $backup->file, []);
    }

    /**
     * Serve download of specific backup snapshot.
     *
     * @param $id integer
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy($id)
    {
        // Check if the user has permission to do this
        $user = Auth::user();
        $allowed_users = ['Daniel Eickhardt', 'Gabrielle Tranchet'];
        if (!in_array($user->name, $allowed_users)) {
            Session::flash('error', "You don't have permission to do that.");
            return redirect()->back();
        }

        $backup = Backup::find($id);

        unlink(storage_path() . '/app/Vocab/' . $backup->file);

        $backup_name = $backup->file;
        $backup->delete();

        Session::flash('success', "The snapshot '" . $backup_name . "' was deleted.");
        return redirect()->back();
    }
}
