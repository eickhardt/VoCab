<?php namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Meaning
 *
 * @property int $id
 * @property int $meaning_type_id
 * @property string|null $root
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $user_id
 * @property-read MeaningType $type
 * @property-read User $user
 * @property-read Collection|Word[] $words
 * @property-read int|null $words_count
 * @method static Builder|Meaning newModelQuery()
 * @method static Builder|Meaning newQuery()
 * @method static \Illuminate\Database\Query\Builder|Meaning onlyTrashed()
 * @method static Builder|Meaning query()
 * @method static Builder|Meaning whereCreatedAt($value)
 * @method static Builder|Meaning whereDeletedAt($value)
 * @method static Builder|Meaning whereId($value)
 * @method static Builder|Meaning whereMeaningTypeId($value)
 * @method static Builder|Meaning whereRoot($value)
 * @method static Builder|Meaning whereUpdatedAt($value)
 * @method static Builder|Meaning whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Meaning withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Meaning withoutTrashed()
 * @mixin Eloquent
 */
class Meaning extends Model
{
    /**
     * This model soft deletes.
     */
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Fillable fields for a word.
     *
     * @var array
     */
    protected $fillable = [
        'word_type_id', 'english',
        'created_at', 'updated_at', 'deleted_at', 'user_id'
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'meanings';

    /**
     * A Meaning has many Words related to it.
     */
    public function words()
    {
        return $this->hasMany('App\Word');
    }

    /**
     * A Meaning belongs to a MeaningType.
     */
    public function type()
    {
        return $this->belongsTo('App\MeaningType', 'meaning_type_id', 'id');
    }

    /**
     * A Meaning belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get a random meaning with it's related words
     */
    public static function random()
    {
        $meaning = new Meaning();

        return $meaning->with('words')->orderByRaw("RAND()")->first();
    }
}