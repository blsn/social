<?php // view, addidng and accept friends

namespace Edchant\Http\Controllers;

use Auth;
use Edchant\Models\User;
use Illuminate\Http\Request; // we use 'Request' to request from post

class FriendController extends Controller
{
	public function getIndex()
	{
		$friends = Auth::user()->friends();
		$requests = Auth::user()->friendRequests(); // adding friendRequests

		//return view('friends.index')->with('friends', $friends);
		
		return view('friends.index')
			->with('friends', $friends)
			->with('requests', $requests);
	}

	public function getAdd($username) // add friend
	{
		$user = User::where('username', $username)->first();

		if(!$user) { // check if the user can be found or not
			return redirect()
				->route('home')
				->with('info', 'That user could not be found.');
		}

		if(Auth::user()->id === $user->id) { // signed-in user can't add himself as a friend (/friends/add/username)
			return redirect()->route('home');
		}

		if(Auth::user()->hasFriendRequestPending($user) || $user->hasFriendRequestPending(Auth::user())) { // check if the user has a friend request pending & if the other user has a request for us (we reverse it..)
			return redirect()
				->route('profile.index', ['username' => $user->username])
				->with('info', 'Friend request already pending.');
		}

		if(Auth::user()->isFriendsWith($user)) { //check if already froends
			return redirect()
				->route('profile.index', ['username' => $user->username])
				->with('info', 'You are already friends.');
		}

		Auth::user()->addFriend($user); // add the firend
		return redirect()
			->route('profile.index', ['username' => $user->username])
			->with('info', 'Friend request sent.');
	}	

	public function getAccept($username) // accept friend request
	{
		$user = User::where('username', $username)->first();

		if(!$user) { // check if the user can be found or not
			return redirect()
				->route('home')
				->with('info', 'That user could not be found.');
		}

		if(!Auth::user()->hasFriendRequestReceived($user)) {
			return redirect()->route('home');

		}

		Auth::user()->acceptFriendRequest($user);

		return redirect()
			->route('profile.index', ['username' => $user->username])
			->with('info', 'Friend request accepted.');
	}	
}

?>
