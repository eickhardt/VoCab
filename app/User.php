<?php namespace App;

use Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Carbon;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $is_first_login
 * @property int $is_admin
 * @property int|null $root_language_id
 * @property int $is_porting
 * @property-read Collection|WordLanguage[] $languages
 * @property-read int|null $languages_count
 * @property-read Collection|Meaning[] $meanings
 * @property-read int|null $meanings_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read WordLanguage|null $rootLanguage
 * @property-read Collection|Word[] $words
 * @property-read int|null $words_count
 * @property-read Collection|Wotd[] $wotds
 * @property-read int|null $wotds_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIsAdmin($value)
 * @method static Builder|User whereIsFirstLogin($value)
 * @method static Builder|User whereIsPorting($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRootLanguageId($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract, AuthorizableContract
{
    use Authenticatable, CanResetPassword, Authorizable, Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'is_first_login', 'is_admin', 'is_porting'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * This user's active languages.
     */
    public function languages()
    {
        return $this->belongsToMany('App\WordLanguage');
    }

    /**
     * This user's default main language (used as default root for meanings and imports).
     */
    public function rootLanguage()
    {
        return $this->belongsTo('App\WordLanguage', 'root_language_id');
    }

    /**
     * This Users Words.
     */
    public function words()
    {
        return $this->hasMany('App\Word');
    }

    /**
     * This Users Word of the Day (s).
     */
    public function wotds()
    {
        return $this->hasMany('App\Wotd');
    }

    /**
     * This Users Meanings.
     */
    public function meanings()
    {
        return $this->hasMany('App\Meaning');
    }

    /**
     * Get a simple array of id's of the languages this user has enabled.
     *
     * @return array
     */
    public function languagesIdArray()
    {
        return $this->languages()->pluck('id')->toArray();
    }

    /**
     * Lock porting for this user. Remember to unlock again!
     */
    public function lockPorting()
    {
        $this->is_porting = true;
        $this->save();
    }

    /**
     * Unlock porting for this user.
     */
    public function unlockPorting()
    {
        $this->is_porting = false;
        $this->save();
    }
}