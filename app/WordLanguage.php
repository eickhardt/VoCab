<?php namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * App\WordLanguage
 *
 * @property int $id
 * @property string $short_name
 * @property string $name
 * @property string $image
 * @property-read Collection|Word[] $words
 * @property-read int|null $words_count
 * @method static Builder|WordLanguage newModelQuery()
 * @method static Builder|WordLanguage newQuery()
 * @method static Builder|WordLanguage query()
 * @method static Builder|WordLanguage whereId($value)
 * @method static Builder|WordLanguage whereImage($value)
 * @method static Builder|WordLanguage whereName($value)
 * @method static Builder|WordLanguage whereShortName($value)
 * @mixin Eloquent
 */
class WordLanguage extends Model
{
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
     * Fillable fields for a language.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'short_name', 'image'
    ];

    /**
     * A WordLanguage has many words related to it.
     */
    public function words()
    {
        return $this->hasMany('App\Word', 'language_id');
    }

    /**
     * Return all languages as a key/value pair array.
     */
    public static function asKeyValuePairs()
    {
        $languages = new WordLanguage;
        $languages = $languages->all();

        $array = [];

        foreach ($languages as $language) {
            $array = Arr::add($array, $language->id, $language->name);
        }

        return $array;
    }
}