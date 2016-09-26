<!doctype html>
<html lang="en">
<title>Gamebook</title>
<head>
	<meta charset="UTF-8">
	<link href="/css/app.css" rel="stylesheet">
</head>

<header>
	@yield('header')
</header>

<body>
	<nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">Gamebook</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
          	@yield('nav')
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

	<div class="container" style="margin-top:-2%;">
		@yield('content')
		<script src="js/bootstrap.min.js"></script>
	</div>
</body>

<footer>
	@yield('footer')
	<div class="container" style="width:500px; margin-top:5%;">
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

<!--Bootstrap core Javascript-->
<script src="/js/app.js"></script>

</html>