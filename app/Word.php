<?php namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;
use Illuminate\Support\Carbon;

/**
 * App\Word
 *
 * @property int $id
 * @property int $language_id
 * @property int $meaning_id
 * @property string $text
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $comment
 * @property int $user_id
 * @property-read WordLanguage $language
 * @property-read Meaning $meaning
 * @property-read User|null $user
 * @method static Builder|Word newModelQuery()
 * @method static Builder|Word newQuery()
 * @method static \Illuminate\Database\Query\Builder|Word onlyTrashed()
 * @method static Builder|Word query()
 * @method static Builder|Word whereComment($value)
 * @method static Builder|Word whereCreatedAt($value)
 * @method static Builder|Word whereDeletedAt($value)
 * @method static Builder|Word whereId($value)
 * @method static Builder|Word whereLanguageId($value)
 * @method static Builder|Word whereMeaningId($value)
 * @method static Builder|Word whereText($value)
 * @method static Builder|Word whereUpdatedAt($value)
 * @method static Builder|Word whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Word withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Word withoutTrashed()
 * @mixin \Eloquent
 */
class Word extends Eloquent
{
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
        'language_id', 'text', 'meaning_id', 'comment', 'user_id'
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
     * A Word belongs to a User.
     */
    public function user()
    {
        return $this->hasOne('App\User');
    }

    /**
     * Grab a random word with it's language and meanings.
     */
    public static function random()
    {
        $word = new Word;
        $word = $word->with('meaning')->with('language')->orderBy(DB::raw('RAND()'))->first();
        return $word;
    }
}