@extends('app')

@section('content')
<h2>Congrats Users!</h2>
This ranking represents how well the user interacts with the games here.<br>
Gaining likes from writing reviews can boost your experience points and get to the top rank!
	<div style="text-align:center;">
		<img src="/assets/system/crown.gif" style="width:15%;height:15%;"><br>
		<label>USER RANKING</label>
		<div class="well" style="height:1500px; overflow:scroll;">
			@foreach($accs as $acc)
				<div class="well" style="width:40%; margin-left:auto;margin-right:auto;">
					<img src="/assets/userdp/{{$acc->picname}}" class="img-circle" style="width:15%;height:15%;"><br>
					<label>{{$acc->first_name}} {{$acc->last_name}}</label><br>
					<div class="progress" style="height:10px;">
						<div class="progress-bar" role="progressbar" aria-valuenow="0"
							<?php 
								$exp = 0;
								if($acc->experience==0)
									$exp=0;
								else
									$exp = 1000/$acc->experience;
							 ?>
							aria-valuemin="0" aria-valuemax="1000" style="width: {{$exp}}%;">
						</div>
					</div>
					<label>LVL {{floor($acc->experience/1000) + 1}}</label>
				</div>
			@endforeach
		</div>
	</div>

@stop