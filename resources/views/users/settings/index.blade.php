@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                <span class="glyphicon glyphicon-cog"></span> Personal settings
            </h2>
        </div>

        <div class="panel-body">
            {!! Form::open(['route' => 'settings_store_root_language_path', 'class' => 'form-horizontal']) !!}
            <h4>Root language</h4>
            <p>This language will be the default language used for the meanings created when importing, and can be used
                as a shortcut when manually creating meanings on the <a href="{{ route('meaning_create_path') }}">create
                    meaning page.</a>
            </p>

            <div class="well well-sm language_well">
                {{ Form::select('root_language_id', $selector_languages, $root_language_id) }}<br><br>

                <button type="submit" class="btn btn-success">
                    <span class="glyphicon glyphicon-ok"></span> Save
                </button>
            </div>
            {!! Form::close() !!}

            <hr>

            <h4>Active languages</h4>
            <p>You can add words in these languages. They will be shown around the site and will be counted in your
                statistics. They will also be exported when using the export button. <br>Disabling languages does not
                delete anything - you can just re-enable them.</p>
            {!! Form::open(['route' => 'settings_store_active_languages_path', 'class' => 'form-horizontal']) !!}

            <div class="well well-sm language_well">
                <button type="submit" class="btn btn-success">
                    <span class="glyphicon glyphicon-ok"></span> Save
                </button>
                <br><br>

                @foreach ($languages as $language)
                    <span class="search_language">
                        {!! Form::checkbox($language->short_name, $language->id, in_array($language->id, $user_languages), ['class' => 'language_checkbox']) !!}
                        <img alt="{{ $language->name }}" src="{{ $language->image }}"> {{ $language->name }}
                    </span>
                    <br>
                @endforeach

                <br>
                <button type="submit" class="btn btn-success">
                    <span class="glyphicon glyphicon-ok"></span> Save
                </button>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@endsection

@section('scripts')
@endsection