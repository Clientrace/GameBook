@extends('app')

@section('content')
<h2>Play For <b><i>FREE</i></b></h2>
<img src="/assets/system/playfree.png" class="img-thumbnail" style="width:40px;height:40px;">
The games here are developed by opensource developer. Enjoy their game and have fun!
<div class="row">

	<div class="col-md-3">
		<div class="well">
			Links<br>
			<div class="well">
				<a href=""><i> Top Games </i></a><br>
				<a href="/filter/puzzle"><i> Puzzle </i></a><br>
				<a href="/filter/action"><i> Action </i></a><br>
				<a href="/filter/strategy"><i> Strategy </i></a><br><br>
			</div>
			Others<br>
			<div class="well">
				<a href=""><i> About </i></a><br>
				<a href=""><i> FAQ </i></a><br>
				<a href=""><i> Developer </i></a><br>
				<a href="/ranking"><i> User Ranking </i></a><br><br>
			</div>
			<div style="align:center;">
				<img src="/assets/system/controller-big.png" style="width:60%;height:50%;">
			</div>
		</div>
	</div>

	<div class="col-md-9">
		<div class="well">
			@if($page==0)
				<div class="well">
					<div style="text-align:center;">
						<img src="/assets/system/banner.jpg" class="img-thumbnail" style="width:100%;height:30%;">
						<h3>Welcome!</h3>
						Play now with the lastest and hotest game in the web!<br>
						If your a developer you can showcase your webgame here<br>
						<i>"Those who live with a sword, die by the sword"</i><br>
						<label>Key Sponsors:</label><br>
					<img src="/assets/system/github.png"  style="width:10%;height:10%;">
					<img src="/assets/system/gitlab.png"  style="width:10%;height:10%;">
					<img src="/assets/system/javascript.png"  style="width:10%;height:10%;">
					</div><br>
					
				</div>
				<label><i>TOP GAMES</i></label>
				<div class="well">
					<div class="row">
						@foreach ($topgames as $game)
							@if(!$game->reviewed)
							<div class="col-md-3" style="width:30%;height:30%;">
								<div class="well">
									<label> {{$game->name}} </label><br>
									<a href="/game/{{$game->id}}"><img src="/assets/gamelogo/{{$game->picname}}" class="img-thumbnail"></a><br>
									<span class="badge">{{$game->genre}}</span><br>
									<a href="/play/{{$game->name}}" class="btn btn-primary" role="button" style="width:100%">Play!</a>
								</div>
							</div>
							@endif
						@endforeach
					</div>
				</div>
			@elseif($page==1)
			<div class="well">
				<b>PUZZLE</b>
				<div class="row">
					@foreach ($games as $game)
						@if(!$game->reviewed)
						<div class="col-md-3" style="width:30%;height:30%;">
							<div class="well">
								<label> {{$game->name}} </label><br>
								<a href="/game/{{$game->id}}"><img src="/assets/gamelogo/{{$game->picname}}" class="img-thumbnail"></a><br>
								<span class="badge">{{$game->genre}}</span><br>
								<a href="/play/{{$game->name}}" class="btn btn-primary" role="button" style="width:100%">Play!</a>
							</div>
						</div>
						@endif
					@endforeach
				</div>
			</div>
			@elseif($page==2)
			<div class="well">
				<b>ACTION</b>
				<div class="row">
					@foreach ($games as $game)
						@if(!$game->reviewed)
						<div class="col-md-3" style="width:30%;height:30%;">
							<div class="well">
								<label> {{$game->name}} </label><br>
								<a href="/game/{{$game->id}}"><img src="/assets/gamelogo/{{$game->picname}}" class="img-thumbnail"></a><br>
								<span class="badge">{{$game->genre}}</span><br>
								<a href="/play/{{$game->name}}" class="btn btn-primary" role="button" style="width:100%">Play!</a>
							</div>
						</div>
						@endif
					@endforeach
				</div>
			</div>
			@elseif($page==3)
			<div class="well">
				<b>STRATEGY</b>
				<div class="row">
					@foreach ($games as $game)
						@if(!$game->reviewed)
						<div class="col-md-3" style="width:30%;height:30%;">
							<div class="well">
								<label> {{$game->name}} </label><br>
								<a href="/game/{{$game->id}}"><img src="/assets/gamelogo/{{$game->picname}}" class="img-thumbnail"></a><br>
								<span class="badge">{{$game->genre}}</span><br>
								<a href="/play/{{$game->name}}" class="btn btn-primary" role="button" style="width:100%">Play!</a>
							</div>
						</div>
						@endif
					@endforeach
				</div>
			</div>
			@endif
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