@extends('app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>
				<span class="glyphicon glyphicon-globe"></span> Home
			</h2>
		</div>

		<div class="panel-body">
			<?php /*@unless (Auth::guest())
				You are logged in! Word of the day is: <b>{!! link_to_route('meaning_wotd_path', $wotd->root) !!}</b><br><br>
				<div class="list-group">
					<div href="#" class="list-group-item active">
						You have access to the following features:
					</div>
					<a href="{{ route('search_path') }}" class="list-group-item">Vocabulary <span class="badge">{{ $wordcount }}</span></a>
				</div>
			@else
			*/ ?>
				<?php /*You are not logged in. Log in <a href="/auth/login">here</a>.*/ ?>
				<div class="jumbotron welcome-box">
					<h1>Welcome to VoCab!</h1>
					<p>Here you can expand your vocabulary in several languages and contribute to a fast growing body of international, up-to-date words and translations.</p>
					<p>
						<a class="btn btn-primary btn-lg" href="/login" role="button"><span class="glyphicon glyphicon-lock"></span> Log in</a> or
						<a class="btn btn-primary btn-lg" href="/register" role="button"><span class="glyphicon glyphicon-pencil"></span> Register</a>
					</p>
				</div>
			<?php /*@endunless*/ ?>
		</div>
	</div>
@endsection
