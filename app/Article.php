<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
	protected $guarded = [];

	public function path()
	{
		return route('articles.show', $this);
	}
	public function edit()
	{
		return route('articles.edit', $this);
	}
	public function download()
	{
		return route('articles.download', $this);
	}
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function files()
	{
		return $this->hasMany(File::class);
	}
}
