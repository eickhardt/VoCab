@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="jumbotron welcome-box">
                <h1>Welcome to Vokapp!</h1>
                <p>Here you can organize your language learning and visualize your progress.</p>
                <p>
                    <a class="btn btn-primary btn-lg" href="{!! route('login') !!}" role="button"><span
                                class="glyphicon glyphicon-lock"></span> Log in</a> or
                    <a class="btn btn-primary btn-lg" href="{!! route('register') !!}" role="button"><span
                                class="glyphicon glyphicon-pencil"></span> Register</a>
                </p>
            </div>
        </div>
    </div>
@endsection
