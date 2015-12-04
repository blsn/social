<?php

namespace Edchant\Http\Controllers;

use Auth;
use Edchant\Models\User;
use Edchant\Models\Status;
use Illuminate\Http\Request; // we use 'Request' to request from post

class StatusController extends Controller
{
	public function postStatus(Request $request) // handling the validation of post status
	{
		$this->validate($request, [ // validate that status exists
			'status' => 'required|max:1000'
		]);
		//dd('status in textarea');

		Auth::user()->statuses()->create([ // create the status in database
			'body' => $request->input('status')
		]);

		return redirect()
			->route('home')
			->with('info', 'Status posted.'); // redirect home after posted
	}

	public function postReply(Request $request, $statusId) // take the request and ststus id that we replying to
	{
		//dd($statusId);
		$this->validate($request, [
			"reply-{$statusId}" => 'required|max:1000'
		], [
			'required' => 'The reply body is required'
		]);
		//dd('all okay');

		$status = Status::notReply()->find($statusId);

		if(!$status) {
			return redirect()->route('home');
		}

		if(!Auth::user()->isFriendsWith($status->user) && Auth::user()->id !== $status->user->id) { // not reply to ourself status
			return redirect()->route('home');
		}

		$reply = Status::create([
			'body' => $request->input("reply-{$statusId}")
		])->user()->associate(Auth::user());

		$status->replies()->save($reply);
		return redirect()->back();
	}

	public function getLike($statusId)
	{
		//dd($statusId);
		$status = Status::find($statusId);

		if(!$status) {
			return redirect()->route('home');
		}

		if(!Auth::user()->isFriendsWith($status->user)) {
			return redirect()->route('home');
		}

		if(Auth::user()->hasLikedStatus($status)) {
			//dd('has already like');
			return redirect()->back();
		}

		$like = $status->likes()->create([]);
		Auth::user()->likes()->save($like);

		return redirect()->back();
	}
}

?>
