<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type='image/x-icon'>
    <title>Vokapp</title>

    <!-- Bootstrap -->
    {{-- <link href="/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
{!! Minify::stylesheet('/css/opentip.css') !!}
{!! Minify::stylesheet('/css/app.css') !!}
{!! Minify::stylesheet('/css/custom.css') !!}
{!! Minify::stylesheet('/css/jquery-ui.css') !!}

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
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            @unless (Auth::guest())
                <div id="navbar-header-shortcuts" class="visible-xs">
                    @include('partials.shortcuts')
                </div>
            @endunless
            <a class="navbar-brand" href="/">Vokapp</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                @unless (Auth::guest())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false"><b>Goto</b> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>{!! link_to_route('meaning_create_path', 'Create meaning') !!}</li>
                            <li>{!! link_to_route('word_create_path', 'Create word') !!}</li>
                            <li class="divider"></li>
                            <li>{!! link_to_route('meaning_wotd_path', 'Word of the day') !!}</li>
                            <li>{!! link_to_route('meaning_random_path', 'Random meaning') !!}</li>
                            <li>{!! link_to_route('word_random_path', 'Random word') !!}</li>
                            <li class="divider"></li>
                            <li>{!! link_to_route('recent_words_path', 'Recent words') !!}</li>
                            <li class="divider"></li>
                            <li>{!! link_to_route('words_trashed_path', 'Trashed words') !!}</li>
                            <li>{!! link_to_route('meanings_trashed_path', 'Trashed meanings') !!}</li>
                            <li class="divider"></li>
                            <li>{!! link_to_route('statistics_path', 'Statistics') !!}</li>
                            @if (Auth::user()->is_admin)
                                <li>{!! link_to_route('backup_show_path', 'Backup') !!}</li>
                            @endif
                        </ul>
                    </li>
                    {!! Form::open(['method' => 'POST', 'role' => 'search', 'id' => 'navbar-search-form', 'class' => 'navbar-form navbar-left', 'route' => 'search_bar_path']) !!}
                    <div class="input-group">
                        <input id="navbar-search-field" autocorrect="off" autocapitalize="none" autocomplete="off"
                               type="text" class="form-control" name="s" placeholder="Search&hellip;">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-default"><span
                                        class="glyphicon glyphicon-search"></span></button>
                        </span>
                    </div>
                    {!! Form::close() !!}
                @endunless
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li>{!! link_to_route('login', 'Login') !!}</li>
                    <li>{!! link_to_route('register', 'Register') !!}</li>
                @else
                    <div id="navbar-shortcuts" class="navbar-form navbar-left hidden-xs">
                        <div class="form-group">
                            @include('partials.shortcuts')
                        </div>
                    </div>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>{!! link_to_route('user_settings_path', 'Settings') !!}</li>
                            <li>{!! link_to_route('import_path', 'Import') !!}</li>
                            <li>{!! link_to_route('export_path', 'Export') !!}</li>
                            <li class="divider"></li>
                            <li><a href="/logout">Logout</a></li>
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
                <div class="alert alert-success" style="display:none;">
                    <strong>Voila!</strong> - {!! Session::pull('success') !!} <br>
                </div>
            @elseif (Session::has('warning'))
                <div class="alert alert-warning" style="display:none;">
                    <strong>OBS!</strong> - {!! Session::pull('warning') !!} <br>
                </div>
            @elseif (Session::has('error'))
                <div class="alert alert-danger" style="display:none;">
                    <strong>Oups!</strong> - {!! Session::pull('error') !!} <br>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script src="/js/opentip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{!! Minify::javascript('/js/bindWithDelay.js') !!}

<script type="text/javascript">
    $(function () {

        $('.alert').slideDown('fast');
        $('.alert').on('click', function () {
            $(this).slideUp('fast');
        });
        $('input').on('focus', function (e) {
            $(this)
                .one('mouseup', function () {
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
