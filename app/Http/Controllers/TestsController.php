<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Mail;

class TestsController extends Controller {

	/**
	 * Attempt to send an email
	 *
	 * @return Response
	 */
	public function mail()
	{
		Mail::send('emails.test', array('testvalue' => 'Pimp'), function($message)
		{
		    $message->to('ddeickhardt@gmail.com', 'John Smith')->subject('Test!');
		});
	}
}
