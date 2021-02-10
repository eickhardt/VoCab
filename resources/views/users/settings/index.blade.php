@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                <span class="glyphicon glyphicon-cog"></span> Settings
            </h2>
        </div>

        <div class="panel-body">
            <div class="panel panel-default">
                <div class="panel-body">

                    {!! Form::open(['route' => 'settings_store_root_language_path', 'class' => 'form-horizontal']) !!}
                    <h4>Root language</h4>
                    <p>This language will be the default language used for the meanings created when importing, and can
                        be
                        used
                        as a shortcut when manually creating meanings on the <a
                                href="{{ route('meaning_create_path') }}">create
                            meaning page.</a>
                    </p>

                    {{ Form::select('root_language_id', $selector_languages, $root_language_id, ['class' => 'root_language_id_select']) }}
                    <br><br>

                    <button type="submit" class="btn btn-success">
                        <span class="glyphicon glyphicon-ok"></span> Save
                    </button>
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-body">

                    <h4>Active languages</h4>
                    <p>You can store words in these languages. They will be shown around the site and will be counted in
                        your
                        statistics. They will also be exported when using the export button. <br>Disabling languages
                        does
                        not
                        delete anything - you can just re-enable them.</p>
                    {!! Form::open(['route' => 'settings_store_active_languages_path', 'class' => 'form-horizontal']) !!}

                    {{ Form::select('working_languages[]', $selector_languages, $user_languages, ['class' => 'working_languages_select', 'multiple' => 'multiple']) }}

                    <br><br>
                    <button type="submit" class="btn btn-success">
                        <span class="glyphicon glyphicon-ok"></span> Save
                    </button>
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.root_language_id_select').select2();
            $('.working_languages_select').select2();
        });
    </script>
@endsection