<?php

namespace Edchant\Models;

use Edchant\Models\Status;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract

{
    use Authenticatable;

    protected $table = 'users';
	
    protected $fillable = [ // the assigned attributes
		'username', 
		'email', 
		'password',
		'first_name',
		'last_name',
		'location'

	];
	
    protected $hidden = [
		'password', 
		'remember_token',
	];
	
	public function getName() 
	{
		if($this->first_name && $this->last_name) { // get user full name
			return "{$this->first_name} {$this->last_name}"; 
		}	
		if($this->first_name) { // otherwise return first name
			return $this->first_name; 
		}	
		return null; // otherwise return null
	}

	public function getNameOrUsername() 
	{
		return $this->getName() ?: $this->username; // return name and if doesn't exist return username
	}

	public function getFirstNameOrUsername() // if we want to address a user for email etc'
	{
		return $this->first_name ?: $this->username; // return first name and if doesn't exist return username
	}

	public function getAvatarUrl()
	{
		//return "https://www.gravatar.com/avatar/{{ md5($this->email) }}?d=mm&s=40"; // doesn't work
		return "http://www.gravatar.com/avatar/" . md5($this->email) . "?d=mm&s=40"; // changed by mb		
	}


	public function statuses() // user can have many statuses, relationship with foreign key
	{
		return $this->hasMany('Edchant\Models\Status', 'user_id');
	}

	public function likes()
	{
		return $this->hasMany('Edchant\Models\Like', 'user_id'); // likes
	}

	public function friendsOfMine()
	{
		return $this->belongsToMany('Edchant\Models\User', 'friends', 'user_id', 'friend_id'); // relate to itself, to 'friends' table by matching 'user_id' and 'friend_id'
	}

	public function friendOf()
	{
		return $this->belongsToMany('Edchant\Models\User', 'friends', 'friend_id', 'user_id'); // same but the other way around
	}

	public function friends() // friend when 'accepted'
	{
		return $this->friendsOfMine()->wherePivot('accepted', true)->get()->merge($this->friendOf()->wherePivot('accepted', true)->get()); // pivot is the 'friends' table
	}

	public function friendRequests()
	{
		return $this->friendsOfMine()->wherePivot('accepted', false)->get();
	}

	public function friendRequestsPending()
	{
		return $this->friendOf()->wherePivot('accepted', false)->get();
	}

	public function hasFriendRequestPending(User $user) // check if current user has a rquest from another user
	{
		return (bool) $this->friendRequestsPending()->where('id', $user->id)->count();
	}

	public function hasFriendRequestReceived(User $user)
	{
		return (bool) $this->friendRequests()->where('id', $user->id)->count();
	}

	public function addFriend(User $user)
	{
		$this->friendOf()->attach($user->id);
	}

	public function acceptFriendRequest(User $user) // update database
	{
		$this->friendRequests()->where('id', $user->id)->first()->pivot->update([
			'accepted' => true
		]);
	}

	public function isFriendsWith(User $user)
	{
		return (bool) $this->friends()->where('id', $user->id)->count();
	}

	public function hasLikedStatus(Status $status)
	{
		/*
		return (bool) $status->likes
			->where('likeable_id', $status->id)
			->where('likeable_type', get_class($status))
			->where('user_id', $this->id)
			->count();
		*/
		return (bool) $status->likes->where('user_id', $this->id)->count();
	}
}
