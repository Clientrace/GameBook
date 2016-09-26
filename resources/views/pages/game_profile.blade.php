@extends('app')

@section('content')
<h2>Play For <b><i>FREE</i></b></h2>
<div class="row">
	<div class="col-md-3">
		<div class="well">
			<h3>{{$game->name}}</h3>
			<img src="/assets/gamelogo/{{$game->picname}}" class="img-thumbnail">
			<a href="/play/{{$game->name}}" class="btn btn-primary" role="button" style="width:100%">Play!</a>
		</div>
	</div>

	<div class="col-md-9">
		<div class="well" style="height:60%;">
			<h3>About</h3>
			Genre: <span class="badge">{{$game->genre}}</span><br>
			Created on: {{$game->date_created}}<br>
			Developer: {{$devinfo[0]->first_name}} {{$devinfo[0]->last_name}} " {{$dev->username}} "
			<div class="well">
				{{$game->description}}
			</div>
		</div>
	</div>

</div>

<div class="well">
	<label>Comment Section</label>
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