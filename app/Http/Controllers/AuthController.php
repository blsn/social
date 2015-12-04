<?php

namespace Edchant\Http\Controllers;

use Auth; // we use 'Auth' to authenticate the user when login
use Edchant\Models\User; // we use 'User' to create new user
use Illuminate\Http\Request; // we use 'Request' to request from post

class AuthController extends Controller
{
	public function getSignup() // register
	{
		return view('auth.signup');
	}

	public function postSignup(Request $request) // post
	{
		//dd('signup');
		$this->validate($request, [ // validate inputs
			'email' => 'required|unique:users|email|max:255', // required, unique, valid email address and max 255 characters
			'username' => 'required|unique:users|alpha_dash|max:20', // Laravel validator for alphabetic characters and spaces (alpha_dash)
			'password' => 'required|min:4'
		]);
		
		//dd('posted okay');
		User::create([ // create a user
			'email' => $request->input('email'),
			'username' => $request->input('username'),
			'password' => bcrypt($request->input('password'))
		]); 
		
		return redirect()->route('home')->with('info', 'Your account has been created and you can now sign in!'); // redirect to home page and flash a message
	}

	public function getSignin() // login
	{
		return view('auth.signin');
	}

	public function postSignin(Request $request) // post
	{
		//dd('posted signin okay');
		$this->validate($request, [
			'email' => 'required',
			'password' => 'required'
		]);
		//dd('validate okay');

		// Auth is in 'config/auth.php', since we moved 'User' to other location, we had to modify it in the Auth file as well (see Authentication Model)
		if(!Auth::attempt($request->only(['email', 'password']), $request->has('remember'))) { // getting only 'email' and 'password', also check if 'remember' has checked
			return redirect()->back()->with('info', 'Could not sign you in with those details!'); // instead back() we can use route('home')
		}

		return redirect()->route('home')->with('info', 'You are now signed in!'); // otherwise... signed in
	}

	public function getSignout() // logout
	{
		Auth::logout();
		return redirect()->route('home')->with('info', 'You have been signed out!'); // redirect to home page and flash a message;
	}
}

?>
