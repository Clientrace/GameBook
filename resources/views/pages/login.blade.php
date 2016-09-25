@extends('app')

@section('content')

<div class="container">
	<form role="form" method="POST" action="/home">
		<div class="form-group">

			<input type="text" class="form-control" name="username" 
				id="name" placeholder="Enter username">

			<input type="password" class="form-control" name="password"
				id="pass" placeholder="Enter password">

			<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn">Login</button>
		</div>
	</form>
</div>

@stop