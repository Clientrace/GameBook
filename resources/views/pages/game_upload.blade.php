@extends('app')

@section('content')

<div class="row">
	<form role="form" method="POST" action="/upload">
		<div class="form-group">
			<input type="text" class="form-control" name="game_name"
				id="name" placeholder="Enter Game Name">
			<textarea class="form-control" name="description"
				id="name" rows="5" cols="50" placeholder="Enter Description Here"></textarea>
			<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn">Submit</button>
		</div>
		<div class="jumbotron">
			<h1>Welcome to landing page!</h1>
				<p>This is an example for jumbotron.</p>
				<p><a class="btn btn-primary btn-lg" role="button">
				Learn more</a>
			</p>
		</div>
	</form>
</div>

@stop