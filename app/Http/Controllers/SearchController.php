<?php

namespace Edchant\Http\Controllers;

use DB;
use Edchant\Models\User;
use Illuminate\Http\Request; // we use 'Request' to request from post

class SearchController extends Controller
{
	public function getResults(Request $request) // we request the search query
	{
		$query = $request->input('query'); // the hyperlink after we make a serach
		//dd($query); // if we search for 'Danny', http://mvc_social_network.dev:8000/search?query=Danny

		if(!$query) { // if query doesn't exists
			return redirect()->route('home');
		}
		$users = User::where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$query}%") // compare 'query' with the full name
		->orWhere('username', 'LIKE', "%{$query}%") // and with the username as well (this will work slow for many users) 
		->get(); 
		//dd($users); // print the users details

		//return view('search.results');
		return view('search.results')->with('users', $users); // pass the users that we found into our view		
	}
}

?>
