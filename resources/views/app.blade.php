<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="favicon.ico" />
	<title>VoCab</title>

	<!-- Bootstrap -->
	<link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/opentip.css" rel="stylesheet" type="text/css" />
	<link href="/css/app.css" rel="stylesheet" type="text/css" />
	<link href="/css/custom.css" rel="stylesheet" type="text/css" />

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
	<link href='/css/jquery-ui.css' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<!-- Navigation -->
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/">VoCab</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<?php /*<li>{!! link_to_route('home', 'Home') !!}</li>*/ ?>
					@unless (Auth::guest()) 
						<li>{!! link_to_route('search_path', 'Search') !!}</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><b>Goto</b> <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li>{!! link_to_route('meaning_create_path', 'Create meaning') !!}</li>
								<li>{!! link_to_route('word_create_path', 'Create word') !!}</li>
								<li class="divider"></li>
								<li>{!! link_to_route('meaning_wotd_path', 'Word of the day') !!}</li>
								<li>{!! link_to_route('meaning_random_path', 'Random meaning') !!}</li>
								<li>{!! link_to_route('word_random_path', 'Random word') !!}</li>
								<li class="divider"></li>
								<li>{!! link_to_route('words_trashed_path', 'Trashed words') !!}</li>
								<li>{!! link_to_route('meanings_trashed_path', 'Trashed meanings') !!}</li>
								<li class="divider"></li>
								<li>{!! link_to_route('statistics_path', 'Statistics') !!}</li>
								<li>{!! link_to_route('backup_show_path', 'Backup') !!}</li>
							</ul>
						</li>
					@endunless
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="/auth/login">Login</a></li>
						<li><a href="/auth/register">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li>{!! link_to_route('user_settings_path', 'Settings') !!}</li>
								<li class="divider"></li>
								<li><a href="/auth/logout">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
	<!-- EO Navigation -->

	<!-- Page -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">

				<!-- Alerts -->
				@if (Session::has('success'))
					<div class="alert alert-success">
						<strong>Voila!</strong> - {{ Session::get('success') }} <br>
					</div>
				@elseif (Session::has('error'))
					<div class="alert alert-danger">
						<strong>Oups!</strong> - {{ Session::get('error') }} <br>
					</div>
				@endif
				<!-- EO Alerts -->

				<!-- Content -->
				@yield('content')
				<!-- EO Content -->
			</div>
		</div>
	</div>
	<!-- EO Page -->

	<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="/js/jquery-ui.min.js"></script>
	<script src="/js/bindWithDelay.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script src="/js/opentip.min.js"></script>
	<?php /*<script src="/js/opentip-styles.js"></script>*/ ?>

	<script type="text/javascript">
		$(function() {
			$('.alert').slideDown();
			$('.alert').on('click', function() 
			{
				$(this).slideUp();
			});
			$('input').on('focus', function (e) 
			{
			    $(this)
			        .one('mouseup', function () 
			        {
			            $(this).select();
			            return false;
			        }).select();
			});
		});
	</script>

	@yield('scripts')
	<!-- EO Scripts -->

</body>
</html>
