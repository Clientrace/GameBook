@extends('app')

@section('content')



@stop

@section('nav')
	<li><a href="/login">{{$user[0]->first_name}}</a></li>
	<li><a href="/logout">Logout<span class="sr-only">(current)</span></a></li>
@stop