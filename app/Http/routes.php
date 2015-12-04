<?php

/*
Route::get('/', function () {
    return view('welcome');
});
*/

/**
* Home
*/
Route::get('/', [
	'uses' => '\Edchant\Http\Controllers\HomeController@index', // class is HomeController @ method is index
	'as' => 'home' // this is a name to be called from the hyperling in navbar
]);

/* example of flash message
Route::get('/alert', function() {
 	return redirect()->route('home')->with('info', 'You have signed up!');
});
*/

/**
* Authentication
*/
Route::get('/signup', [ // register
	'uses' => '\Edchant\Http\Controllers\AuthController@getSignup',
	'as' => 'auth.signup',
	'middleware' => ['guest'] // logged in user can't access this link (only guest), 'guest' is coming from 'Http/Requests/Kernel.php', and redirect to 'RedirectIfAuthenticated.php'
]);

Route::post('/signup', [
	'uses' => '\Edchant\Http\Controllers\AuthController@postSignup',
	'middleware' => ['guest']
]);

Route::get('/signin', [ // login
	'uses' => '\Edchant\Http\Controllers\AuthController@getSignin',
	'as' => 'auth.signin',
	'middleware' => ['guest']
]);

Route::post('/signin', [
	'uses' => '\Edchant\Http\Controllers\AuthController@postSignin',
	'middleware' => ['guest']
]);

Route::get('/signout', [ // logout
	'uses' => '\Edchant\Http\Controllers\AuthController@getSignout',
	'as' => 'auth.signout'
]);

/**
* Search
*/
Route::get('/search', [ // search
	'uses' => '\Edchant\Http\Controllers\SearchController@getResults',
	'as' => 'search.results'
]);

/**
* User profile
*/
Route::get('/user/{username}', [ // profile
	'uses' => '\Edchant\Http\Controllers\ProfileController@getProfile',
	'as' => 'profile.index',
	'middleware' => ['auth'] // added by mb
]);

Route::get('/profile/edit', [
	'uses' => '\Edchant\Http\Controllers\ProfileController@getEdit', // edit user profile
	'as' => 'profile.edit',
	'middleware' => ['auth'] // if we are not signed in we cannot access this route
]);

Route::post('/profile/edit', [
	'uses' => '\Edchant\Http\Controllers\ProfileController@postEdit',
	'middleware' => ['auth']
]);

/**
* Friends
*/
Route::get('/friends', [ // friends page
	'uses' => '\Edchant\Http\Controllers\FriendController@getIndex', 
	'as' => 'friend.index',
	'middleware' => ['auth'] 
]);

Route::get('/friends/add/{username}', [ // add friends
	'uses' => '\Edchant\Http\Controllers\FriendController@getAdd', 
	'as' => 'friend.add',
	'middleware' => ['auth'] 
]);

Route::get('/friends/accept/{username}', [ // accept friend
	'uses' => '\Edchant\Http\Controllers\FriendController@getAccept', 
	'as' => 'friend.accept',
	'middleware' => ['auth'] 
]);

/**
* Statuses
*/
Route::post('/status', [ // post status
	'uses' => '\Edchant\Http\Controllers\StatusController@postStatus', 
	'as' => 'status.post',
	'middleware' => ['auth'] 
]);

Route::post('/status/{statusId}/reply', [ // status reply
	'uses' => '\Edchant\Http\Controllers\StatusController@postReply', 
	'as' => 'status.reply',
	'middleware' => ['auth'] 
]);

Route::get('/status/{statusId}/like', [ // like
	'uses' => '\Edchant\Http\Controllers\StatusController@getLike', 
	'as' => 'status.like',
	'middleware' => ['auth'] 
]);
