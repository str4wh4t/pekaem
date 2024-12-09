<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="description" content="Robust admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template.">
	<meta name="keywords" content="admin template, robust admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
	<meta name="author" content="PIXINVENT">

	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>@yield('title','APLIKASI PKM UNDIP')</title>

	<link rel="apple-touch-icon" href="{{ asset('assets/template/robust/app-assets/images/ico/apple-icon-120.png') }}">
	<link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/template/robust/app-assets/images/ico/favicon.ico') }}">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CMuli:300,400,500,700" rel="stylesheet">

	@include('template.global_css')

</head>
<body class="vertical-layout vertical-compact-menu 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-compact-menu" data-col="2-columns">

	@include('template.top_nav')

	@include('template.side_menu')

	<div class="app-content content">
		<div class="content-wrapper">

			@yield('content')

		</div>
	</div>

	@include('template.footer')

	@include('template.global_js')

</body>
</html>