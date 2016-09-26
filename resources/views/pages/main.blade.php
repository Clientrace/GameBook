@extends('app')

@section('content')
<h2>Play For <b><i>FREE</i></b></h2>
The games here are developed by opensource developer. Enjoy their game and have fun!
<div class="row">

	<div class="col-md-3">
		<div class="well">
			Genre<br>
			<a href=""><i> Top Game </i></a><br>
			<a href=""><i> Puzzle </i></a><br>
			<a href=""><i> Action </i></a><br>
			<a href=""><i> Strategy </i></a>
		</div>
	</div>

	<div class="col-md-9">
		<div class="well">
			<div class="well">
				<div style="text-align:center;">
					<h2>Welcome!</h2>
					Play now with the lastest and hotest game in the web!<br>
					If your a developer you can showcase your webgame here<br>

					<i>"Those who live with a sword, die by the sword"</i>
				</div>
			</div>
			<label><i>TOP GAMES</i></label>
			<div class="well">
				<div class="row">
					@foreach ($topgames as $game)
						@if(!$game->reviewed)
						<div class="col-md-3" style="width:30%;">
							<div class="well">
								<label> {{$game->name}} </label><br>
								<a href="/game/{{$game->id}}"><img src="assets/gamelogo/{{$game->picname}}" class="img-thumbnail"></a><br>
								<span class="badge">{{$game->genre}}</span><br>
								<a href="/play/{{$game->name}}" class="btn btn-primary" role="button" style="width:100%">Play!</a>
							</div>
						</div>
						@endif
					@endforeach
				</div>
			</div>
		</div>

		<div class="well">
			<label>RECENTLY ADDED</label>
			
		</div>
	</div>

</div>

@stop

@section('nav')
	<li class="active"><a href="/">HOME</a></li>
	@if($log!=true)
		<li><a href="/login">SIGN IN</a></li>
		<li><a href="/register">SIGN UP<span class="sr-only">(current)</span></a></li>
	@else
		<li><a href="/userprofile">{{$user[0]->first_name}}</a></li>
		<li><a href="/logout">Logout<span class="sr-only">(current)</span></a></li>
	@endif
@stop