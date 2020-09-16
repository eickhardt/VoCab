<?php namespace App;

use Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
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
 * @property array languages
 * @property integer id
 * @property string name
 * @property array words
 * @property string $email
 * @property string $password
 * @property boolean $is_first_login
 * @property boolean $is_admin
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int|null $languages_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read int|null $words_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
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
    protected $fillable = ['name', 'email', 'password', 'is_first_login', 'is_admin'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * This user's preferred languages.
     */
    public function languages()
    {
        return $this->belongsToMany('App\WordLanguage');
    }

    /**
     * This Users Words.
     */
    public function words()
    {
        return $this->hasMany('App\Word');
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
        $languages = $this->languages;

        $languages_id_array = [];
        foreach ($languages as $language) {
            $languages_id_array[] = $language->id;
        }
        return $languages_id_array;
    }
}