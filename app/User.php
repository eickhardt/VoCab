<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, AuthorizableContract {

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
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * This user's preffered languages.
	 */
	public function languages()
    {
        return $this->belongsToMany('App\WordLanguage');
    }

    /**
     * Get a simple array of id's of the languages this user does not want displayed.
     */
    public function languages_id_array()
    {
    	$languages = $this->languages;

    	$languages_id_array = [];
    	foreach ($languages as $language) 
    	{
    		$languages_id_array[] = $language->id;
    	}
    	return $languages_id_array;
    }
}