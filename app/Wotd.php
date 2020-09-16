<?php namespace App;

use Artisan;
use Auth;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Wotd
 *
 * @property int $id
 * @property string $date
 * @property int $meaning_id
 * @property-read Meaning $meaning
 * @method static Builder|Wotd newModelQuery()
 * @method static Builder|Wotd newQuery()
 * @method static Builder|Wotd query()
 * @method static Builder|Wotd whereDate($value)
 * @method static Builder|Wotd whereId($value)
 * @method static Builder|Wotd whereMeaningId($value)
 * @mixin Eloquent
 */
class Wotd extends Model
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
    protected $table = 'wotds';

    /**
     * Fillable fields for a wotd.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'meaning_id', 'user_id'
    ];

    /**
     * A word of the day has one meaning.
     */
    public function meaning()
    {
        return $this->belongsTo('App\Meaning');
    }

    /**
     * Get the current word of the day as an instance of Meaning.
     *
     * @return Meaning|null
     */
    public static function getCurrent()
    {
        $user_id = Auth::user()->id;

        // Check if there are any wotd set for this user
        $wotd_exists = Wotd::where('user_id', $user_id)->count();

        // If no wotd has been set yet for this user, do it
        if (!$wotd_exists) {
            Artisan::call('setwordofday', compact('user_id'));
        }

        $wotd = Wotd::where('user_id', $user_id)
            ->orderBy('date', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();

        return Meaning::with('words')
            ->find($wotd->meaning_id);
    }
}