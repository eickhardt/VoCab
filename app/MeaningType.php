<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class MeaningType extends Model {

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
	protected $table = 'meaning_types';

	/**
	 * A type covers many meanings.
	 */
	public function meanings()
    {
        return $this->hasMany('App\Meaning');
    }

    /**
     * Return all types as a key/value pair array.
     */
    public static function asKeyValuePairs()
    {
    	$types = new MeaningType;
    	$types = $types->all();

    	$array = [];

    	foreach ($types as $type) 
    	{
    		$array = array_add($array, $type->id, ucfirst($type->name));
    	}

    	return $array;
    }
}
