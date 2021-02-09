@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                <span class="glyphicon glyphicon-minus-sign"></span> Export
            </h2>
        </div>

        <div class="panel-body">
            <p>Press the button below to export meanings and words in your currently active languages to a CSV file. You
                can see which
                languages you have activated on
                the {{ link_to_route('user_settings_path', 'settings page.') }}
            </p>
            <p>More information about the CSV format is available on
                the {{ link_to_route('import_path', 'import page.') }}
            </p>

            {!! Form::open(['route' => 'export_do_path', 'class' => 'form-horizontal']) !!}

            <div class="form-group">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-success">
                        <span class="glyphicon glyphicon-minus-sign"></span> Export
                    </button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('scripts')
@endsection