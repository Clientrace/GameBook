@extends('app')

@section('content')

<h2><img src="/assets/system/controller.png" class="img-thumbnail" style="width:6%;height:6%;"> PLAY</h2>
<img src="/assets/system/banner.jpg" class="img-thumbnail" style="width:100%;height:200px;">
<div class="well">
	<label>ALL GAMES</label>
	<div class="row">
		@foreach ($games as $game)
			@if(!$game->reviewed)
			<div class="col-md-3">
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