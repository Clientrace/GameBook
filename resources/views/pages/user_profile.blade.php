@extends('app')

@section('content')

<h2><img src="assets/system/profile-icon.png" style="width:5%;height:5%;"> Profile </h2>
<div class="container">
	<div class="well">
		<div class="row">
			<div class="col-md-3">
				<div class="well" style="height:450px;">
					<img src="assets/userdp/{{$user[0]->picname}}" class="img-thumbnail" style="width:200px;height:200px;">
					
					<form method="POST" action="/userprofile" enctype="multipart/form-data">
						<input name="file" type="file"/>
						<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-primary btn-sm">Upload</button>
					</form>

					<h3> {{$acc->username}} </h4>
					<h5> {{$user[0]->first_name}} {{$user[0]->last_name}}</h3>
					@if($user[0]->experience<1000)
						<h4> LEVEL: 1</h4>
					@else
						<h4> LEVEL: {{($user[0]->experience/1000) + 1}}</h4>
					@endif

					<div class="progress" style="height:2%;">
						<div class="progress-bar" role="progressbar" aria-valuenow="0"
							<?php 
								$exp = 0;
								if($user[0]->experience==0)
									$exp=0;
								else
									$exp = 1000/$user[0]->experience;
							 ?>
							aria-valuemin="0" aria-valuemax="1000" style="width: {{$exp}}%;">
						</div>
					</div>

				</div>
			</div>

			<div class="col-md-9">
			@if($acc->type==1)
			<div class="well">
					<b>Game Uploads </b>
					<div class="well" style="overflow:scroll;">
						<div class="row">
							@foreach ($likes as $like)
								<div class="col-md-2">
									<?php $game = $games->where('id',$like->gameid)->first();?>
									<a href="/game/{{$like->gameid}}"><img src="/assets/gamelogo/{{$game->picname}}" class="img-thumbnail"></a>
								</div>
							@endforeach
						</div>
					</div>
					<a href="/upload" class="btn btn-default btn-lg" role="button">Upload</a>
				</div>
			@endif
				<div class="well">
					<b>Reviews </b>
					<div class="well" style="overflow:scroll;">
						@foreach ($comments as $comment)
							<div class="well" style="width:80%;">
								<div class="row">
									<?php $game = $games->where('id',$comment->gameid)->first();?>
									<label>{{$game->name}} : </label>
									{{$comment->description}}
								</div>
								<div class="row">
									<span class="badge">{{$comment->date_created}}</span>
								</div>
							</div>
						@endforeach
					</div>
				</div>
				<div class="well">
					<b>Likes </b>
					<div class="well" style="overflow:scroll;">
						<div class="row">
							@foreach ($likes as $like)
								<div class="col-md-2">
									<?php $game = $games->where('id',$like->gameid)->first();?>
									<a href="/game/{{$like->gameid}}"><img src="/assets/gamelogo/{{$game->picname}}" class="img-thumbnail"></a>
								</div>
							@endforeach
						</div>
					</div>
				</div>
				<div class="well">
					<b>Dislikes </b>
					<div class="well" style="overflow:scroll;">
						<div class="row">
							@foreach ($dislikes as $dislike)
								<div class="col-md-2">
									<?php $game = $games->where('id',$dislike->gameid)->first();?>
									<a href="/game/{{$dislike->gameid}}"><img src="/assets/gamelogo/{{$game->picname}}" class="img-thumbnail"></a>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop

@section('nav')
	<li><a href="/">HOME</a></li>
	<li class="active"><a href="/userprofile">{{$user[0]->first_name}}</a></li>
	<li><a href="/logout">Logout<span class="sr-only">(current)</span></a></li>
@stop