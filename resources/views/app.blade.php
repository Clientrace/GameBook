<!doctype html>
<html lang="en">
<title>Gamebook</title>
<head>
	<meta charset="UTF-8">
	<link href="css/app.css" rel="stylesheet">
</head>

<header>
	@yield('header')
</header>

<body>
	<script src="https://code.jquery.com/jquery.js"></script>
	<div class="container">
		<h2>Gamebook</h2>
		@yield('content')
		<script src="js/bootstrap.min.js"></script>
	</div>
</body>

<footer>
	@yield('footer')
	<div class="container" style="width:500px;">
		Copyright Gamebook (c) 2016
		Project Management Final Project
		<a href="">Developers</a> |
		<a href="">About</a> |
		<a href="">FAQ</a> |
		<a href="">Manual</a> |
		<a href="">Terms & Conditions</a> <br>
		Site Version 1.4
	</div>
</footer>

</html>