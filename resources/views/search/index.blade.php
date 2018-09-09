@extends('app')

@section('content')
	<?php isset($s) ?: $s = 0; ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>
				<span class="glyphicon glyphicon-search"></span> Search 
				<span id="waitmsg">/ <span class="glyphicon glyphicon-hourglass"></span> Searching...</span>
			</h2>
		</div> 
		<div id="words" class="panel-body">
			<a href="{{ route('meaning_create_path') }}" type="submit" class="btn btn-success">
				<span class="glyphicon glyphicon-plus-sign"></span> Create word
			</a>
			<a href="{{ route('meaning_wotd_path') }}" type="submit" class="btn btn-primary">
				<span class="glyphicon glyphicon-certificate"></span> Word of the Day
			</a>
			<?php /*
			<a href="{{ route('statistics_path2') }}" type="submit" class="btn btn-primary">
				<span class="glyphicon glyphicon-certificate"></span> NEW STATS
			</a>
			*/ ?>
			<a href="{{ route('statistics_path') }}" type="submit" class="btn btn-primary">
				<span class="glyphicon glyphicon-stats"></span> Statistics
			</a>
			<a href="{{ route('recent_words_path') }}" type="submit" class="btn btn-primary">
				<span class="glyphicon glyphicon-stats"></span> Recent words
			</a>
			<?php /* At the moment we only want to show backup for the admins */ ?>
			@if (Auth::user()->name == 'Gabrielle Tranchet' || Auth::user()->name == 'Daniel Eickhardt')
				<a href="{{ route('backup_show_path') }}" type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-hdd"></span> Backup
				</a>
			@endif
			<a id="advanced_search_btn" type="submit" class="btn btn-info">
				<span class="glyphicon glyphicon-cog Search settings"></span> Settings
			</a>
			<br><br>

			<div id="search_settings" class="panel panel-default">
				<div class="panel-heading">
					<h3 class="search_settings_header"><span class="glyphicon glyphicon-cog Search settings"></span> Search settings</h3>
				</div>
				<div class="panel-body">
					<h4>Languages</h4>
					<div class="well well-sm language_well">
						@foreach ($languages as $language)
							<span class="search_language"><img src="{{ $language->image }}"> {!! Form::checkbox($language->short_name, $language->id, !in_array($language->id, $user_languages), ['class' => 'language_checkbox']) !!}</span>
						@endforeach
					</div>
					<br>
					<h4>Types</h4>
					<div class="well well-sm type_well">
						@foreach ($types as $type)
							<span class="search_language">{{ ucfirst($type->name) }}s {!! Form::checkbox($type->name, $type->id, true, ['class' => 'type_checkbox']) !!}</span>
						@endforeach
					</div>
				</div>
			</div>

			<input id="searchbar" class="form-control" placeholder="Search for..." />

			<ul id="words_table" class="list-group words_list">
				<li id="cloneme" class="list-group-item" style="display:none;"></li>
				<li id="noresult" class="list-group-item" style="display:none;">There seems to be nothing here.</li>
			</ul>
		</div>
		<?php /*<img id="awesome" class="img-responsive" src="/img/awesome.png">*/ ?>
		<div class="splash-border">
			<img id="awesome" class="img-responsive meanings" src="/img/words.jpg">
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(function() 
		{
			// For holding our opentips
			var openTips = {};

			// Show advanced options when the corresponsing button is clicked
			$('#advanced_search_btn').on('click', function() 
			{
				$('#search_settings').slideToggle();
			});

			// Variables we will need for the ajax requests
			var method = 'POST';
			var url = '<?= route('ajax_word_search_path', [], false) ?>';
			var translations_url = '<?= route('ajax_simple_meaning_path', [], false) ?>';
			var _globalObj = <?= json_encode(array('_token'=> csrf_token())) ?>;
			var token = _globalObj._token;

			var languages = <?= $languages->toJson() ?>;

			// When the page loads initially, check if there is something in the search field and if so, perform the search
			if ($('#searchbar').val().length > 1)
			{
				var search_term = $('#searchbar').val();
				updateList2(search_term, token, method, url, languages, getOptions());
			}

			// When the user types something in the field, perform the search after they stopped typing for a short while 
			// This delay is to avoid problems with requests being sent instantly every time a button is pressed
			$('#searchbar').bindWithDelay('input propertychange paste', function() 
			{
				var search_term = $('#searchbar').val();
				updateList2(search_term, token, method, url, languages, getOptions());
			}, 300);

			// Click event handler displaying all translations of a word
			$(document).on('click', '.translations', function() 
			{
				var meaning_id = $(this).data('id');

				// Check if the tip already exists
				if (openTips.hasOwnProperty(meaning_id) && openTips[meaning_id])
				{
					// Tip already exists, deactivate it
					openTips[meaning_id].deactivate();
					openTips[meaning_id] = null;
				}
				else {
					// Create the tip
					var tip = new Opentip(
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
					openTips[meaning_id] = tip;
				}
			});

			// Check if there is something to search for already
			var s = '{{ $s }}';
			if (s != '0')
			{
				$('#searchbar').val(s);
				var search_term = $('#searchbar').val();
				updateList2(search_term, token, method, url, languages, getOptions());
			}
		});

		// Get the advanced search options
		function getOptions() 
		{
			var options = {
				types: '',
				languages: ''
			};

			// Array of types to exclude
			var types = [];
			$('.type_checkbox').each(function() 
			{
				if (!$(this).is(':checked'))
				{
					types.push($(this).val());
				}
			});
			options.types = types;

			// Array of languages to exclude
			var languages = [];
			$('.language_checkbox').each(function() 
			{
				if (!$(this).is(':checked'))
				{
					languages.push($(this).val());
				}
			});
			options.languages = languages;

			return JSON.stringify(options);
		}

		// Update the list based on the information provided by the user
		function updateList2(search_term, token, method, url, languages, options)
		{
			$('.removeme').remove();

			if (search_term.length > 2)
			{
				$('#words_table').hide();
				$('#waitmsg').show();

				$.ajax({
					type: method,
					url: url,
					data: { search_term: search_term, _token: token, options: options },
					success: function(words) {
						if (words.length > 0)
						{
							for (var i = 0; i <= words.length -1; i++) 
							{
								var edit_link = "/words/" + words[i]['meaning_id'] + "/edit";
								var row = $('#cloneme').clone().removeAttr('id').removeAttr('style').addClass('removeme');
								row.html( '<div class="btn btn-xs btn-primary translations" data-id="' + words[i]['meaning_id'] + '"><span class="glyphicon glyphicon-list"></span></div><img class="row_image" src="' + languages[words[i]['language_id']-1].image + '"> ' + '<a href="'+ edit_link +'">' + words[i]['text'] + '</a>');

								row.prependTo($('.words_list'));
							}
							$('#noresult').hide();
						}
						else 
						{
							$('#noresult').show().css('display', 'block');
						}
						$('#waitmsg').hide();
						$('#awesome').slideUp();
						$('#words_table').slideDown();
					}
				});
			}
			else
			{
				$('#words_table').slideUp();
				$('#awesome').slideDown();
			}
		}
	</script>
@endsection