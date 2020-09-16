@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                @if (isset($list_type) && $list_type == 'Random')
                    <span class="glyphicon glyphicon-question-sign"></span>
                @elseif (isset($list_type) && $list_type == 'Word of the Day')
                    <span class="glyphicon glyphicon-certificate"></span>
                @elseif (isset($list_type) && $list_type == 'Trashed')
                    <span class="glyphicon glyphicon-trash"></span>
                @else
                    <span class="glyphicon glyphicon-list-alt"></span>
                @endif
                Meanings / List / <b>{{ isset($list_type) ? $list_type : $meanings[0]->root }}</b>
            </h2>
        </div>
        <div class="panel-body">

            @forelse ($meanings as $meaning)
                <div class="panel panel-default">
                    <div class="panel-body">

                        @if (isset($list_type) && $list_type == 'Trashed')
                            <?php /* We don't want any words to show up if it's the trashed list */ ?>
                        @else
                            <h4>Words</h4>
                            <ul class="list-group">
                                @foreach ($languages as $language)
                                    <li class="list-group-item">
                                        <img class="meaning_words_flag" src="{{ $language->image }}"
                                             alt="{{ $language->name }}">

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
                                           class="badge"><span
                                                    class="glyphicon glyphicon glyphicon-plus-sign"></span></a>
                                        <span class="badge">{{ $count }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <h4>Word information</h4>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-0">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        Root: <b>{{ $meaning->root }}</b>
                                    </li>
                                    <li class="list-group-item">
                                        Type name: <b>{{ ucfirst($meaning->type->name) }}</b>
                                    </li>
                                    <li class="list-group-item">
                                        Real type: <b>{{ $meaning->real_word_type }}</b>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 col-md-offset-0">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        Id: <b>{{ $meaning->id }}</b>
                                    </li>
                                    <li class="list-group-item">
                                        Created at {{ date("F j, Y, g:i a", strtotime($meaning->created_at)) }}
                                    </li>
                                    <li class="list-group-item">
                                        Last updated at {{ date("F j, Y, g:i a", strtotime($meaning->updated_at)) }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        @if (isset($list_type) && $list_type == 'Trashed')
                            <a href="{{ route('meaning_restore_path', $meaning->id) }}" type="submit"
                               class="btn btn-success">
                                <span class="glyphicon glyphicon glyphicon-refresh"></span> Restore meaning
                            </a>
                        @else
                            <a href="{{ route('meaning_edit_path', $meaning->id) }}" type="submit"
                               class="btn btn-success">
                                <span class="glyphicon glyphicon glyphicon-pencil"></span> Edit
                            </a>
                        @endif

                    </div>
                </div>
            @empty
                <ul class="list-group">
                    <li class="list-group-item">There seems to be nothing here.</li>
                </ul>
            @endforelse


            @if (isset($list_type) && $list_type == 'Random')
                <a href="{{ route('meaning_random_path') }}" type="submit" class="btn btn-primary">
                    <span class="glyphicon glyphicon-question-sign"></span> Another one
                </a>
            @endif

            <a href="{{ route('search_path') }}" type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-search"></span> Goto search
            </a>
        </div>
    </div>
@endsection
