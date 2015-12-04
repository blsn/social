@extends('templates.default')

@section('content')
	<div class="row">
		<div class="col-lg-6">
			<h3>Your friends</h3>
			<!-- list of of riends -->
			@if(!$friends->count())
				<p>Youe have no friends.</p>
			@else
				@foreach($friends as $user)
					@include('user/partials/userblock')
				@endforeach
			@endif
		</div>
		<div class="col-lg-6">
			<h4>Friend requests</h4>
			<!-- list of riend requests -->
			@if(!$requests->count())
				<p>You have no friends requests.</p>
			@else
				@foreach($requests as $user)
					@include('user.partials.userblock')
				@endforeach
			@endif
		</div>
	</div>
@stop