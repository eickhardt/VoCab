@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                <span class="glyphicon glyphicon-cog"></span> Personal settings
            </h2>
        </div>

        <div class="panel-body">
            {!! Form::open(['route' => 'settings_store_path', 'class' => 'form-horizontal']) !!}
            <h4>Select active languages</h4>

            <button type="submit" class="btn btn-success">
                <span class="glyphicon glyphicon-ok"></span> Save
            </button>
            <br>
            <br>

            <div class="well well-sm language_well">
                @foreach ($languages as $language)
                    <span class="search_language">
                        {!! Form::checkbox($language->short_name, $language->id, in_array($language->id, $user_languages), ['class' => 'language_checkbox']) !!}
                        <img alt="{{ $language->name }}" src="{{ $language->image }}"> {{ $language->name }}
                    </span>
                    <br>
                @endforeach
            </div>
            <br>
            <button type="submit" class="btn btn-success">
                <span class="glyphicon glyphicon-ok"></span> Save
            </button>
            {!! Form::close() !!}

        </div>
    </div>
@endsection

@section('scripts')
@endsection