<?php namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;

class Word extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'words';

	/**
	 * Fillable fields for a word.
	 *
	 * @var array
	 */
	protected $fillable = [
		'language_id', 'text', 'meaning_id', 'comment'
	];

	/**
	 * This model soft deletes.
	 */
	use SoftDeletes;
	protected $dates = ['deleted_at'];

	/**
	 * A Word is in one language.
	 */
	public function language()
    {
        return $this->belongsTo('App\WordLanguage', 'language_id', 'id');
    }

    /**
	 * A Word belongs to a meaning.
	 */
	public function meaning()
    {
    	return $this->belongsTo('App\Meaning');
    }

    /**
	 * Grab a random word with it's language and meanings
	 */
	public static function random()
    {
    	$word = new Word;
    	$word = $word->with('meaning')->with('language')->orderBy(DB::raw('RAND()'))->first();
        return $word;
    }
}