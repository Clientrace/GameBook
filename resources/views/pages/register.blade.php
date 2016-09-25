@extends('app')

@section('content')

<div class="container" style="width:500px;">
	<form role="form" method="POST" action="/register">
		<div class="form-group">
			<label for="type">User Info</label>
			<div class="container">
				<input type="text" class="form-control" name="first_name"
					id="name" placeholder="Enter firstname">

				<input type="text" class="form-control" name="last_name"
					id="lastname" placeholder="Enter lastname">
			</div>

			<div class="container">
				<input type="text" class="form-control" name="username" 
					id="username" placeholder="Enter username">

				<input type="password" class="form-control" name="password"
					id="pass" placeholder="Enter password">
			</div>

			<label for="birthday">Birthdate</label>

			<select name="month" class="form-control">
				<option>January</option>
				<option>February</option>
				<option>March</option>
				<option>April</option>
				<option>May</option>
				<option>June</option>
				<option>July</option>
				<option>August</option>
				<option>September</option>
				<option>October</option>
				<option>November</option>
				<option>December</option>
			</select>

			<select name="day" class="form-control">
				@for ($i=1;$i<32;$i++)
					<option>{{$i}}</option>
				@endfor
			</select>

			<select name="year" class="form-control">
				@for ($i=0;$i<100;$i++)
					<option>{{$year-$i}}</option>
				@endfor
			</select>

			<label for="type">Account Type</label>

			<select class="form-control" name="type">
				<option>USER</option>
				<option>DEVELOPER</option>
			</select>

			<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn">Submit</button>
		</div>
	</form>
</div>


@stop