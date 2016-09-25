@extends('app')

@section('content')

<div class="container">
	@if ($regi)
		<h2> Registered Succesfully! </h2>
	@endif
	<div class="container" style="width: 40%; margin-top:5%;">
		<form role="form" method="POST" action="/home">
			<div class="form-group">

				<h3><b> SIGN IN! </b></h3>

				<input type="text" class="form-control" name="username" 
					id="name" placeholder="Enter username">

				<input type="password" class="form-control" name="password"
					id="pass" placeholder="Enter password">

			</div>
			<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-primary">Login</button>
		</form>
	</div>
</div>

@stop

@section('nav')
	<li><a href="/">HOME</a></li>
	<li class="active"><a href="/login">SIGN IN</a></li>
	<li><a href="/register">SIGN UP<span class="sr-only">(current)</span></a></li>
@stop