@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading hidden-xs hidden-sm">
            <h2>
                <span class="glyphicon glyphicon-search"></span> Search
                <span id="waitmsg">/ <span class="glyphicon glyphicon-hourglass"></span> Searching...</span>
                <a id="advanced_search_btn" type="submit" class="btn btn-info pull-right advanced_search_btn">
                    <span class="glyphicon glyphicon-cog"></span>
                </a>
            </h2>
        </div>
        <div id="words" class="panel-body">
            <div id="search_settings" class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="search_settings_header"><span class="glyphicon glyphicon-cog Search settings"></span>
                        Search settings</h3>
                </div>
                <div class="panel-body">
                    <h4>Types</h4>
                    <div class="well well-sm type_well">
                        @foreach ($types as $type)
                            <span class="search_language">{{ ucfirst($type->name) }}s {!! Form::checkbox($type->name, $type->id, true, ['class' => 'type_checkbox']) !!}</span>
                        @endforeach
                    </div>
                    <br>
                    <h4>Languages</h4>
                    <div class="well well-sm language_well">
                        @foreach ($languages as $language)
                            <span class="search_language">
                                {!! Form::checkbox($language->short_name, $language->id, in_array($language->id, $user_languages), ['class' => 'language_checkbox']) !!} <img
                                        alt="{{ $language->name }}" src="{{ $language->image }}"> {{ $language->name }}
                            </span><br>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="input-group showbtn-xs">
                <input id="searchbar" class="form-control" autocapitalize="none" autocomplete="off"
                       placeholder="Search" value="{{ $s }}"/>
                <span class="input-group-btn visible-xs visible-sm">
					<button type="submit" class="btn btn-default"><span
                                class="glyphicon glyphicon-search"></span></button>
					<a id="advanced_search_btn" type="submit" class="btn btn-info advanced_search_btn">
						<span class="glyphicon glyphicon-cog"></span>
					</a>
				</span>
            </div>

            <ul id="words_table" class="list-group words_list">
                <li id="cloneme" class="list-group-item" style="display:none;"></li>
                <li id="noresult" class="list-group-item" style="display:none;">There seems to be nothing here.</li>
            </ul>
        </div>
        <?php /*<img id="awesome" class="img-responsive" src="/img/awesome.png">*/ ?>
        <div class="splash-border">
            <img id="awesome" alt="word pile" class="img-responsive meanings" src="/img/words.jpg">
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            // For holding our opentips
            let openTips = {};

            // Show advanced options when the corresponding button is clicked
            $('.advanced_search_btn').on('click', function () {
                $('#search_settings').slideToggle('fast');
            });

            // Variables we will need for the ajax requests
            let method = 'POST';
            let url = '<?= route('ajax_word_search_path', [], false) ?>';
            let translations_url = '<?= route('ajax_simple_meaning_path', [], false) ?>';
            let _globalObj = <?= json_encode(array('_token' => csrf_token())) ?>;
            let token = _globalObj._token;
            let languages = <?= $languages->toJson() ?>;
            let searchBar = $('#searchbar');

            // When the user types something in the field, perform the search after they stopped typing for a short while
            // This delay is to avoid problems with requests being sent instantly every time a button is pressed
            searchBar.bindWithDelay('input propertychange paste', function () {
                let search_term = searchBar.val();
                updateList2(search_term, token, method, url, languages, getOptions());
            }, 300);

            // Click event handler displaying all translations of a word
            $(document).on('click', '.translations', function () {
                let meaning_id = $(this).data('id');

                // Check if the tip already exists
                if (openTips.hasOwnProperty(meaning_id) && openTips[meaning_id]) {
                    // Tip already exists, deactivate it
                    openTips[meaning_id].deactivate();
                    openTips[meaning_id] = null;
                } else {
                    // Create the tip
                    openTips[meaning_id] = new Opentip(
                        $(this),
                        {
                            target: $(this).parent().find('a'),
                            // target: true,
                            tipJoint: "left",
                            background: "white",
                            borderColor: "lightgray",
                            showOn: 'creation',
                            offset: [5, 0],
                            hideTrigger: 'closeButton',
                            closeButtonRadius: 10,
                            closeButtonCrossSize: 5,
                            closeButtonCrossColor: '#337ab7',
                            removeElementsOnHide: true,
                            ajax: translations_url + '/' + meaning_id
                        }
                    );
                }
            });

            $(document).on("change", ".language_checkbox", function () {
                let search_term = searchBar.val();
                updateList2(search_term, token, method, url, languages, getOptions());
            });

            $(document).on("change", ".type_checkbox", function () {
                let search_term = searchBar.val();
                updateList2(search_term, token, method, url, languages, getOptions());
            });

            // Check if there is something to search for already
            let s = '{{ $s }}';
            if (s !== '0') {
                searchBar.val(s);
                let search_term = searchBar.val();
                updateList2(search_term, token, method, url, languages, getOptions());
            }
        });

        // Get the advanced search options
        function getOptions() {
            let options = {
                types: '',
                languages: ''
            };

            // Array of types to exclude
            let types = [];
            $('.type_checkbox').each(function () {
                if (!$(this).is(':checked')) {
                    types.push($(this).val());
                }
            });
            options.types = types;

            // Array of languages to include
            let languages = [];
            $('.language_checkbox').each(function () {
                if ($(this).is(':checked')) {
                    languages.push($(this).val());
                }
            });
            options.languages = languages;

            return JSON.stringify(options);
        }

        // Update the list based on the information provided by the user
        function updateList2(search_term, token, method, url, languages, options) {
            $('.removeme').remove();

            if (search_term.length > 1) {
                $('#words_table').hide('fast');
                $('#waitmsg').show('fast');

                $.ajax({
                    type: method,
                    url: url,
                    data: {search_term: search_term, _token: token, options: options},
                    success: function (words) {
                        if (words.length > 0) {
                            for (let i = 0; i <= words.length - 1; i++) {
                                console.log(languages);
                                let edit_link = "/meaning/" + words[i]['meaning_id'] + "/edit";
                                let row = $('#cloneme').clone().removeAttr('id').removeAttr('style').addClass('removeme');
                                if (languages[words[i]['language_id'] - 1]) {
                                    row.html('<div class="btn btn-xs btn-primary translations" data-id="'
                                        + words[i]['meaning_id']
                                        + '"><span class="glyphicon glyphicon-list"></span></div><img class="row_image" alt="flag" src="'
                                        + languages[words[i]['language_id'] - 1].image
                                        + '"> ' + '<a href="'
                                        + edit_link
                                        + '">'
                                        + words[i]['text']
                                        + '</a>');
                                }

                                row.prependTo($('.words_list'));
                            }
                            $('#noresult').hide('fast');
                        } else {
                            $('#noresult').show().css('display', 'block');
                        }
                        $('#waitmsg').hide('fast');
                        $('#awesome').slideUp('fast');
                        $('#words_table').slideDown('fast');
                    }
                });
            } else {
                $('#words_table').slideUp();
                $('#awesome').slideDown();
            }
        }
    </script>
@endsection