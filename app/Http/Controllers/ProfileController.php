<?php

namespace Edchant\Http\Controllers;

use Auth;
use Edchant\Models\User;
use Illuminate\Http\Request; // we use 'Request' to request from post

class ProfileController extends Controller
{
	public function getProfile($username)
	{
		//dd($username); print username

		$user = User::where('username', $username)->first(); // check if exists
		if(!$user) {
			abort(404);
		}

		$statuses = $user->statuses()->notReply()->get(); // pull all our statuses and we don't include reply

		//return view('profile.index');
		//return view('profile.index')->with('user', $user);
		return view('profile.index')
			->with('user', $user)
			->with('statuses', $statuses)
			->with('authUserIsFriend', Auth::user()->isFriendsWith($user));
	}

	public function getEdit() // edit user profile
	{
		return view('profile.edit');
	}

	public function postEdit(Request $request)
	{
		$this->validate($request, [ // validate user update form
			'first_name' => 'alpha|max:50',
			'last_name' => 'alpha|max:50',
			'location' => 'max:20'
		]);
		//dd('validate update okay');

		Auth::user()->update([ // database update user profile 
			'first_name' => $request->input('first_name'),
			'last_name' => $request->input('last_name'),
			'location' => $request->input('location')
		]);

		return redirect()->route('profile.edit')->with('info', 'Your profile has been updated.');
	}
}

?>
