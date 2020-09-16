<?php namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * App\MeaningType
 *
 * @property int $id
 * @property string $name
 * @property-read Collection|Meaning[] $meanings
 * @property-read int|null $meanings_count
 * @method static Builder|MeaningType newModelQuery()
 * @method static Builder|MeaningType newQuery()
 * @method static Builder|MeaningType query()
 * @method static Builder|MeaningType whereId($value)
 * @method static Builder|MeaningType whereName($value)
 * @mixin Eloquent
 */
class MeaningType extends Model
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

        foreach ($types as $type) {
            $array = Arr::add($array, $type->id, ucfirst($type->name));
        }

        return $array;
    }
}
