<?php namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Backup
 *
 * @property int $id
 * @property int $user_id
 * @property string $file
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @method static Builder|Backup newModelQuery()
 * @method static Builder|Backup newQuery()
 * @method static Builder|Backup query()
 * @method static Builder|Backup whereCreatedAt($value)
 * @method static Builder|Backup whereFile($value)
 * @method static Builder|Backup whereId($value)
 * @method static Builder|Backup whereUpdatedAt($value)
 * @method static Builder|Backup whereUserId($value)
 * @mixin Eloquent
 */
class Backup extends Model
{
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