<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Response;
use Session;
use Artisan;
use Auth;

use App\Backup;

use App\Word;
use App\Meaning;

class BackupController extends Controller {

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
		if (!in_array($user->name, $allowed_users))
		{
			Session::flash('error', "You don't have permission to do that.");
			return redirect()->back();
		}

		// Create the backup
		Artisan::call('backup:run');

		// Create a corresponding database row
		$backup_path = storage_path().'/app/backups/';
		$files = scandir($backup_path, SCANDIR_SORT_DESCENDING);
		$newest_file = $files[0];
		$backup = Backup::create(['user_id' => $user->id, 'file' => $newest_file]);
		
		// Redirect back with a message to the user
		Session::flash('success', "A new snapshot has been created.");
		return redirect()->back();
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
	 * @return Response
	 */
	public function download($id)
	{
		$backup = Backup::find($id);
		return Response::download(storage_path().'/app/backups/'.$backup->file, $backup->file, []);
	}


	/**
	 * Serve download of specific backup snapshot.
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		// Check if the user has permission to do this
		$user = Auth::user();
		$allowed_users = ['Daniel Eickhardt', 'Gabrielle Tranchet'];
		if (!in_array($user->name, $allowed_users))
		{
			Session::flash('error', "You don't have permission to do that.");
			return redirect()->back();
		}

		$backup = Backup::find($id);

		unlink(storage_path().'/app/backups/'.$backup->file);

		$backup_name = $backup->file;
		$backup->delete();

		Session::flash('success', "The snapshot '".$backup_name."' was deleted.");
		return redirect()->back();

	}


	public function mwdata1()
	{
		return Response::download(storage_path().'/app/meanings.json', 'meanings.json', []);
	}

	public function mwdata2()
	{
		return Response::download(storage_path().'/app/words.json', 'words.json', []);
	}
}
