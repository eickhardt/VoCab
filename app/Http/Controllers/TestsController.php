<?php namespace App\Http\Controllers;

use Mail;
use Auth;

class TestsController extends Controller
{
    /**
     * Attempt to send an email
     *
     * @return void
     */
    public function mail()
    {
        Mail::send('emails.test', array('testvalue' => 'Pimp'), function ($message) {
            $message->to('ddeickhardt@gmail.com', 'John Smith')->subject('Test!');
        });
    }

    public function languages()
    {
        $languages = Auth::user()->languages;
        dd($languages);
    }
}
