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
			<img src="/assets/system/controller-md.png" class="img-thumbnail" style="width:15%;height:15%;">
			<h3>About</h3>
			Genre: <span class="badge">{{$game->genre}}</span><br>
			Created on: {{$game->date_created}}<br>
			Developer: {{$devinfo[0]->first_name}} {{$devinfo[0]->last_name}} " {{$dev->username}} "<br>
			<span class="badge">{{$game->likes}}</span><img src="/assets/system/like.png" class="img-thumbnail" style="width:5%;height:5%;">
			<span class="badge">{{$game->likes}}</span><img src="/assets/system/dislike.png"class="img-thumbnail"  style="width:5%;height:5%;">
			<div class="well">
				{{$game->description}}
			</div>
		</div>
	</div>
</div>

<div class="well">
	<label>Comment Section</label>
	<div class="well" style="overflow:scroll;height:200px;">
		@foreach ($comments as $comment)
			<div class="well" style="width:80%;">
				<div class="row">
					<?php $commentor = $accs->find($comment->userid); ?>
					<?php $commentorinfo = $accsinfo->where('user_id',$commentor->id);?>
					<img src="/assets/userdp/{{$commentorinfo[0]->picname}}" class="img-circle" style="width:5%;height:5%;">
					<label>{{$commentor->username}} : </label>
					{{$comment->description}}
				</div>
				<div class="row">
					<span class="badge">{{$comment->date_created}}</span>
				</div>
			</div>
		@endforeach
	</div>
	<div class="well" style="width:60%;">
		<form role="form" method="POST" action="/game/{{$game->id}}">
			<div class="form-group">
				<textarea class="form-control" name="description" 
					id="name" placeholder="Add a comment"></textarea>
			</div>
			<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-primary">Comment</button>
		</form>
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