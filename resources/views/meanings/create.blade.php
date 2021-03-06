@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                <span class="glyphicon glyphicon-plus-sign"></span> Meanings / Create
            </h2>
        </div>

        <div class="panel-body">
            <div class="panel panel-default">
                <div class="panel-body">
                    {!! Form::open(['route' => 'meaning_store_path', 'class' => 'form-horizontal']) !!}

                    <div class="form-group {{ $errors->has('root') ? 'has-error' : '' }}">
                        <label class="col-md-4 control-label">Root</label>
                        <div class="col-md-6">
                            {!! Form::text('root', null, ['class' => 'form-control', 'autocapitalize' => 'none']) !!}
                            {!! $errors->first('root', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('meaning_type_id') ? 'has-error' : '' }}">
                        <label class="col-md-4 control-label">Type</label>
                        <div class="col-md-6">
                            {!! Form::select('meaning_type_id', $types, null, ['class' => 'form-control', 'id' => 'type_selector']) !!}
                            {!! $errors->first('meaning_type_id', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    @foreach ($languages as $language)
                        <div class="form-group {{ $errors->has($language->short_name) ? 'has-error' : '' }}">
                            <label class="col-md-4 control-label">
                                @if ($language->id == Auth::user()->root_language_id)
                                    <span class="glyphicon glyphicon-tree-conifer"></span>
                                @endif
                                <img alt="{{ $language->name }}" src="{{ $language->image }}">
                            </label>
                            <div class="col-md-6">
                                {!! Form::text($language->short_name, null, ['class' => 'form-control', 'autocapitalize' => 'none']) !!}
                                {!! $errors->first($language->short_name, '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    @endforeach

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-success">
                                <span class="glyphicon glyphicon-plus-sign"></span> Create
                            </button>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

            <a href="{{ route('search_path') }}" type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-search"></span> Goto search
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('#type_selector').on('change', function () {
                $('.real_type').val($('#type_selector').val() + '00').focus();
            });
        });
    </script>
@endsection