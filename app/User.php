<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'email', 'nickname', 'password', 'type'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $guarded = [
		'email', 'remember_token'
	];
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */

	public function articles()
	{
		return $this->hasMany(Article::class); //select * from articles where user_id = 
	}

	public function files()
	{
		return $this->hasMany(File::class); //select * from files where user_id = 
	}
}

//$user = User::find(1); // select * from user where id = 1
