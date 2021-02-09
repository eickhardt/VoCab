@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                <span class="glyphicon glyphicon-plus-sign"></span> Import
                <a id="help_btn" type="submit" class="btn btn-info pull-right advanced_search_btn">
                    <span class="glyphicon glyphicon-question-sign"></span>
                </a>
            </h2>
        </div>

        <div class="panel-body">
            <div id="help_panel" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="search_settings_header"><span class="glyphicon glyphicon-question-sign"></span>
                        Help</h3>
                </div>
                <div class="panel-body">
                    <p><b>Use <code>;</code> as delimiter for language columns, and <code>,</code> for words of the same
                            language.</b> Example CSV file with header:</p>
                    <pre><code>#meaning_type_id  #english    #french
2;dog;chien
1;black,dark;noir</code></pre>
                    <p>The header is
                        optional and only
                        for
                        humans
                        to read. Remember to set
                        the form header checkbox accordingly.</p>
                    <p>The example file has english as column 1, french as column 2 and will import 2 meanings. The
                        first
                        meaning will
                        have
                        2 words, one in english and one in french. <br>The second will have 2 english words (black &
                        dark)
                        and one
                        french (noir), 3 words in total. </p>
                    <br>

                    <p><b>Use <code>##</code>:</b> Right after a word to separate the word from the word comment. Word
                        comments are optional.
                        Example:</p>
                    <pre><code>2;dog<b><u>##Dogs are cute!</u></b>;chien</code></pre>

                    <br>
                    <p><b>Use <code>\\</code>:</b> To escape the <code>;</code> or <code>,</code> characters.</p>
                    <pre><code>2;dog##Dogs<b><u>\\,</u></b> just great friends!;chien<b><u>\\;</u></b>chienne</code></pre>

                    <br>
                    <p>The first column in the CSV file is optional, and contains the id of the meaning type as
                        seen in the table.
                        If the checkbox is unchecked,
                        all imported meanings will have the "other" type.</p>
                    <pre><code>| id | name
+----+-----------
| 1  | adjective
| 2  | noun
| 3  | verb
| 4  | adverb
| 5  | other</code></pre>
                    <br>

                    <p><b>File size:</b> Max size of the import file is <code>2mb</code>.</p>
                </div>
            </div>

            {!! Form::open(['route' => 'import_do_path', 'files'=>'true', 'class' => 'form-horizontal']) !!}

            <div class="form-group {{ $errors->has('csv_file') ? 'has-error' : '' }}">
                <label class="col-md-4 control-label">CSV file</label>
                <div class="col-md-6">
                    {{ Form::file('csv_file') }}
                    {!! $errors->first('csv_file', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            {{--            <div class="form-group {{ $errors->has('ignore_header') ? 'has-error' : '' }}">--}}
            {{--                <label class="col-md-4 control-label">Ignore header</label>--}}
            {{--                <div class="col-md-6">--}}
            {{--                    {{ Form::checkbox('ignore_header', 1, true, ['class' => 'language_checkbox']) }} Ignore the first--}}
            {{--                    line--}}
            {{--                    {!! $errors->first('ignore_header', '<span class="help-block">:message</span>') !!}--}}
            {{--                </div>--}}
            {{--            </div>--}}

            {{--            @foreach ($columns as $column)--}}
            {{--                @if ($loop->index == 3)--}}
            {{--                    <div class="form-group">--}}
            {{--                        <div class="col-md-6 col-md-offset-4">--}}
            {{--                            <div id="extra_columns_button" class="btn btn-info">--}}
            {{--                                <span class="glyphicon glyphicon-arrow-down"></span> More language columns--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                @endif--}}
            {{--                <div class="form-group {{ $loop->index >= 3 ? 'extra_column' : '' }} {{ $errors->has($column) ? 'has-error' : '' }}">--}}
            {{--                    <label class="col-md-4 control-label">Column {{ $loop->index + 1 }}</label>--}}
            {{--                    <div class="col-md-6">--}}
            {{--                        {{ Form::select($column, $languages, null, ['placeholder' => 'Pick a language']) }}--}}
            {{--                        {!! $errors->first($column, '<span class="help-block">:message</span>') !!}--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            @endforeach--}}

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-success">
                        <span class="glyphicon glyphicon-plus-sign"></span> Import
                    </button>
                </div>
            </div>

            {!! Form::close() !!}

            <hr>

            {!! Form::open(['route' => 'delete_all_path', 'class' => 'form-horizontal']) !!}
            <p>You may want to clear your data before importing. This will force delete all your meanings and
                words, including trashed and languages you do not have activated. <b><u>This action
                        cannot be
                        undone.</u></b></p>
            <p>You currently have {{ $meanings_count }} meanings and {{ $words_count }} words, including trashed.</p>
            <div class="form-group {{ $errors->has('confirm_delete_all') ? 'has-error' : '' }}">
                <label class="col-md-4 control-label">Confirmation</label>
                <div class="col-md-6">
                    {{ Form::text('confirm_delete_all', null, ['placeholder' => 'Type "delete" here']) }}
                    {!! $errors->first('confirm_delete_all', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-danger">
                        <span class="glyphicon glyphicon-ban-circle"></span> Force delete all
                    </button>
                </div>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {

            // Show help when the corresponding button is clicked
            $('#help_btn').on('click', function () {
                $('#help_panel').slideToggle('fast');
            });

            // Show more columns when the corresponding button is clicked
            $('#extra_columns_button').on('click', function () {
                $('.extra_column').slideToggle('fast');
            });
        });
    </script>
@endsection