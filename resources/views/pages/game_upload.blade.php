@extends('app')

@section('content')

<div class="container">
	<h3>Game Upload!</h3>
	<form role="form" method="POST" action="/upload">
		<div class="form-group">
			<img src="" class="img-thumbnail">
			<input name="toProcess" type="file"/>
			<label>GAME NAME: </label>
			<input type="text" class="form-control" name="game_name"
				id="name" placeholder="Enter Game Name">
			<label>GENRE: </label><br>
			<div class="btn-group">
				<select name="month" class="form-control" style="width:120px;">
					<option>PUZZLE</option>
					<option>ACTION</option>
					<option>STRATEGY</option>
				</select>
			</div><br>
			<label>DESCRIPTION: </label>
			<textarea class="form-control" name="description"
				id="name" rows="5" cols="50" placeholder="Enter Description Here"></textarea>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="accept"> I accept <a href>Terms & Conditions</a>
				</label>
			</div>
			<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-primary btn-lg">Submit</button>
		</div>
	</form>
</div>

@stop