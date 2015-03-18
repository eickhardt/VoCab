<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class WordLanguage extends Model {

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
	protected $table = 'word_languages';

	/**
	 * A WordLanguage has many words related to it.
	 */
	public function words()
    {
        return $this->hasMany('App\Word');
    }

	/**
     * Return all languages as a key/value pair array.
     */
    public static function asKeyValuePairs()
    {
    	$languages = new WordLanguage;
    	$languages = $languages->all();

    	$array = [];

    	foreach ($languages as $language) 
    	{
    		$array = array_add($array, $language->id, $language->name);
    	}

    	return $array;
    }
}