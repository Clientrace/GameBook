@extends('app')

@section('content')
<h2>Welcome!</h2>
Play now with the lastest and hotest game in the web!<br>
<div class="well">
	<label>TOP GAMES</label>
</div>

<div class="well">
	<label>RECENTLY ADDED</label>
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