@extends('app')

@section('content')

	<h3>{{$account->username}}</h3>
	<h3>{{$userinfo[0]->first_name}}</h3>
	<div class="progress">
		<div class="progres-bar" aria-valuenow="60" aria-valuemin="0" 
		aria-valuemax="100" style="width:60%'">
			<span class="sr-only">60% Complete</span>
		</div>
	</div>

@stop