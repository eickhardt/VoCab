<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Word;

class Wotd extends Model {

	/**
	 * This model does not have timestamps.
	 *
	 * @var boolean
	 */
	public $timestamps = false;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'wotds';

	/**
	 * Fillable fields for a wotd.
	 *
	 * @var array
	 */
	protected $fillable = [
		'date', 'meaning_id'
	];

	/**
	 * A word of the day has one meaning.
	 */
	public function meaning()
    {
        return $this->belongsTo('App\Meaning');
    }

	/**
	 * Get the current word of the day as an instance of Word.
	 */
	public static function getCurrent()
	{
		$wotd = new Wotd;
		$wotd = $wotd->orderBy('date', 'DESC')->first();

		if ($wotd)
			$meaning = Meaning::with('words')->find($wotd->meaning_id);
		else
			$meaning = Meaning::random();

		return $meaning;
	}
}