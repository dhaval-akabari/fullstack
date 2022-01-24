<!DOCTYPE HTML>
<html lang="zxx">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>
	@include('partials.header')
</head>

<body>
	<div id="app2">
		@include('partials.nav')

		@yield('content')
	</div>

	@include('partials.footer')

</body>

</html>