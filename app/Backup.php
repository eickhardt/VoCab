<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model {

	/**
	 * Fillable fields for a word.
	 *
	 * @var array
	 */
	protected $fillable = [
		'file', 'user_id', 'created_at', 'updated_at'
	];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'backups';

	/**
	 * A Word is in one language.
	 */
	public function user()
    {
        return $this->belongsTo('App\User');
    }
}