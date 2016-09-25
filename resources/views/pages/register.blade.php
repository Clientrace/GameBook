@extends('app')

@section('content')

<div class="container" style="width:500px;">
	<form role="form" method="POST" action="/register">
		<div class="form-group">
			<h3><b>Register!</b></h3>

			<label for="birthday">Name:</label>
			<div class="container" style="width:400px;">
				
				@if (count($errors) > 1)
					@if (strpos($errorlist,'null_uname'))
						<span class="badge">This Field is required</span>
					@endif
				@endif
				
				<input type="text" class="form-control" name="username" 
					id="username" placeholder="Enter username">

				@if (count($errors) > 1)
					@if (strpos($errorlist,'null_firstname'))
						<span class="badge">This Field is required</span>
					@endif
				@endif

				<input type="text" class="form-control" name="first_name"
					id="name" placeholder="Enter firstname">


				@if (count($errors) > 1)
					@if (strpos($errorlist,'null_lastname'))
						<span class="badge">This Field is required</span>
					@endif
				@endif

				<input type="text" class="form-control" name="last_name"
					id="lastname" placeholder="Enter lastname">
			</div>
			<label for="birthday">Password:</label>
			<div class="container" style="width:400px;">

				@if (count($errors) > 1)
					@if (strpos($errorlist,'null_pass'))
						<span class="badge">This Field is required</span>
					@elseif (strpos($errorlist,'pass0'))
						<span class="badge">Password is too short</span>
					@elseif (strpos($errorlist,'pass1'))
						<span class="badge">Password not match</span>
					@endif
				@endif

				<input type="password" class="form-control" name="password"
					id="pass" placeholder="Enter password">

				@if (count($errors) > 1)
					@if (strpos($errorlist,'null_cpass'))
						<span class="badge">This Field is required</span>
					@endif
				@endif

				<input type="password" class="form-control" name="cpassword"
					id="cpass" placeholder="Confirm password">
			</div>

			<label for="birthday">Birthdate: </label>
			<div class="container" style="width:400px;">
				<div class="btn-group">
					<select name="month" class="form-control" style="width:120px;">
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
				</div>
				<div class="btn-group">
					<select name="day" class="form-control" style="width:100px;">
						@for ($i=1;$i<32;$i++)
							<option>{{$i}}</option>
						@endfor
					</select>
				</div>
				<div class="btn-group">
					<select name="year" class="form-control" style="width:100px;">
						@for ($i=0;$i<100;$i++)
							<option>{{$year-$i}}</option>
						@endfor
					</select>
				</div>
			</div>

			<label for="type">Account Type</label>
			<div class="containter">
				<div class="btn-group" name="type">
					<label class="btn btn-primary">
						<input type="radio" name="type" id="option1" checked="checked" value="USER"> USER
					</label>

					<label class="btn btn-primary">
						<input type="radio" name="type" id="option2" value="DEVELOPER"> DEVELOPER
					</label>

				</div>
			</div>

			<div class="checkbox">
				<label>
					<input type="checkbox" name="accept"> I accept <a href>Terms & Conditions</a>
					@if (count($errors) > 1)
						@if (strpos($errorlist,'accept'))
							<span class="badge">You must accept our terms & conditions</span>
						@endif
					@endif
				</label>
			</div>

			<button type="submit" name="_token" value="{{ csrf_token() }}" class="btn btn-primary btn-lg">Submit</button>
		</div>
	</form>
</div>


@stop