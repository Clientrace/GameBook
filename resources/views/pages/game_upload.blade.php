@extends('app')

@section('content')

<div class="container" style="width:60%;">
	<h3>Game Upload!</h3>
	<div class="well">
		@if($page==0)
			<img src="/assets/system/dev-logos.png" class="img-thumbnail" style="width:350;height:150">
			Developers can upload their own Javascript Games here on Gamebook<br>
			and share it to every gamers in the world!<br>
			Read the <a href="">terms and conditions</a>.
			<ul class="pager">
				<li class="next"><a href="/upload/0">Next &rarr;</a></li>
			</ul>
		@elseif($page==1)
			<label>JAVASCRIPT GAME</label><br>
			Upload ZIP javascript game with proper file structure: <a href="">see here</a>
			<img src="/assets/system/directory.png" class="img-thumbnail" style="width:100;height:150">
			<form method="POST" action="/upload" enctype="multipart/form-data">
				<input name="file" type="file"/>
				<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-primary btn-sm">Upload</button>
			</form>
		@elseif($page==2)
			<form role="form" method="POST" action="/upload2" enctype="multipart/form-data">
				<div class="form-group">
					<label>Enter Game Description</label>
					<div class="well">
						<label>GAME LOGO</label><br>
						<img src="/assets/system/controller.png" class="img-thumbnail" style="width:100px;height:100px;">
						<input name="file" type="file"/>
						<label>GAME NAME: </label>
						<input type="text" class="form-control" name="game_name"
							id="name" placeholder="Enter Game Name">
						<label>GENRE: </label><br>
						<div class="btn-group">
							<select name="genre" class="form-control" style="width:120px;">
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
					</div>
					<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-primary btn-lg">Submit</button>
				</div>
			</form>
		@endif
	</div>
</div>

@stop