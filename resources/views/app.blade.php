<!doctype html>
<html lang="en">
<title>GameBook</title>
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
		<h2>GameBook</h2>
		@yield('content')
		<script src="js/bootstrap.min.js"></script>
	</div>
</body>

<footer>
	@yield('footer')
</footer>

</html>