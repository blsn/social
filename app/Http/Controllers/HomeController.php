<?php

namespace Edchant\Http\Controllers;

use Auth;
use Edchant\Models\Status;

class HomeController extends Controller
{
	public function index()
	{
		if(Auth::check()) { // if user signed-in show another page
			//$statuses = Status::where(function($query) {
			$statuses = Status::notReply()->where(function($query) {			
				return $query->where('user_id', Auth::user()->id)
					->orWhereIn('user_id', Auth::user()
					->friends()->lists('id'));
			})
			->orderBy('created_at', 'desc')
			->paginate(2); // see 'timeline/index.blade.php' for render the pagination
			//dd($statuses);

			return view('timeline.index')->with('statuses', $statuses);
		}
		return view('home');
	}
}

?>
