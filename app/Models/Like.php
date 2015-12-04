<?php

namespace Edchant\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
	protected $table = 'likeable';

	public function likeable()
	{
		return $this->morphTo();
	}

	public function likes()
	{
		return $this->belongsTo('Edchant\Models\User', 'user_id');
	}
}

?>