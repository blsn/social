<?php

namespace Edchant\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
	protected $table = 'statuses';

    protected $fillable = [ // the assigned attributes
		'body' 
	];

	public function user() // relationship to relate back to the user
	{
		return $this->belongsTo('Edchant\Models\User', 'user_id');
	}

	public function scopeNotReply($query)
	{
		return $query->whereNull('parent_id');
	}

	public function replies()
	{
		return $this->hasMany('Edchant\Models\Status', 'parent_id');
	}

	public function likes()
	{
		return $this->morphMany('Edchant\Models\Like', 'likeable');
	}
}

?>