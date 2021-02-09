@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                <span class="glyphicon glyphicon glyphicon-pencil"></span> Meanings / Edit / <b>{{ $meaning->root }}</b>
            </h2>
        </div>

        <div class="panel-body">
            <h4>Words</h4>
            <ul class="list-group">
                @foreach ($languages as $language)
                    <li class="list-group-item">
                        <img class="meaning_words_flag" src="{{ $language->image }}" alt="{{ $language->name }}">

                        <?php
                        $count = 0;
                        $links_array = [];
                        ?>

                        @foreach ($meaning->words as $word)
                            @if ($word->language_id == $language->id)
                                <?php
                                $entry = link_to_route('word_edit_path', $word->text, $word->id);
                                if ($word->comment)
                                    $entry .= ' - ' . $word->comment;
                                $links_array[] = $entry;
                                $count++;
                                ?>
                            @endif
                        @endforeach

                        {!! implode(', ', $links_array) !!}

                        <a href="{{ route('word_create_path') }}?meaning_id={{ $meaning->id }}&language_id={{ $language->id }}"
                           class="badge"><span class="glyphicon glyphicon glyphicon-plus-sign"></span></a>
                        <span class="badge">{{ $count }}</span>
                    </li>
                @endforeach
            </ul>

            <h4>Meaning information</h4>
            <div class="panel panel-default">
                <div class="panel-body">
                    {!! Form::model($meaning, [
                        'route'  => ['meaning_update_path', $meaning->id],
                        'method' => 'PATCH',
                        'class'  => 'form-horizontal'
                    ]) !!}

                    <div class="form-group {{ $errors->has('meaning_type_id') ? 'has-error' : '' }}">
                        <label class="col-md-4 control-label">Type</label>
                        <div class="col-md-6">
                            {!! Form::select('meaning_type_id', $types, $meaning->meaning_type_id, [
                                'class' => 'form-control',
                                'id' => 'type_selector'
                            ]) !!}
                            {!! $errors->first('meaning_type_id', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('root') ? 'has-error' : '' }}">
                        <label class="col-md-4 control-label">Root</label>
                        <div class="col-md-6">
                            {!! Form::text('root', NULL, ['class' => 'form-control']) !!}
                            {!! $errors->first('root', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('created_at') ? 'has-error' : '' }}">
                        <label class="col-md-4 control-label">Created at</label>
                        <div class="col-md-6">
                            {!! Form::text('created_at', NULL, ['class' => 'form-control', 'disabled']) !!}
                            {!! $errors->first('created_at', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('updated_at') ? 'has-error' : '' }}">
                        <label class="col-md-4 control-label">Updated at</label>
                        <div class="col-md-6">
                            {!! Form::text('updated_at', NULL, ['class' => 'form-control', 'disabled']) !!}
                            {!! $errors->first('updated_at', '<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-success">
                                <span class="glyphicon glyphicon-ok-sign"></span> Save
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}

                    {!! Form::open(['route' => ['meaning_delete_path', $meaning->id], 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'delete_word_form']) !!}
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button id="delete_meaning_btn" type="submit" class="btn btn-primary btn-danger">
                                <span class="glyphicon glyphicon-trash"></span> Trash
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

            $("#delete_meaning_btn").on('click', function (e) {
                $(this).button("disable");
                if (confirm("Are you sure you want to trash this meaning? The words associated will also be trashed.")) {
                    $('#delete_word_form').submit();
                } else {
                    $(this).button("enable");
                }
                e.preventDefault();
            });
        });
    </script>
@endsection