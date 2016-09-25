@extends('app')

@section('content')

<h2> Profile </h2>
<div class="container">
	<div class="row">
		<div class="col-md-6 col-lg-4">
			<div class="well" style="height:450px;">
				<img src="assets/dp.jpg" class="img-circle" style="width:150px;height:150px;">
				<form method="POST" action="/userprofile" enctype="multipart/form-data">
					<input name="file" type="file"/>
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
						aria-valuemin="0" aria-valuemax="1000" style="width: {{$user[0]->experience%1000}}%;">
					</div>
				</div>

			</div>
		</div>
		<div class="col-md-6 col-lg-8">
			<div class="well" style="height:450px;">
				<b>Games Played </b>
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