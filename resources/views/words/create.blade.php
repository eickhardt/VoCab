@extends('app')

@section('content')
	<?php isset($meaning) ? $meaning_id = $meaning->id : $meaning_id = 0; ?>
	<?php isset($language_id) ?: $language_id = 0; ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2><span class="glyphicon glyphicon-plus-sign"></span> Translations / Create</h2>
		</div>

		<div id="main-body" class="panel-body">

			@if (isset($meaning))
				<div id="words" class="panel panel-default">
					<div class="panel-body">
						<p>Translations with the same root: </p>
						@foreach($meaning->words as $word)
							<?php $links[] = link_to_route('word_edit_path', $word->text, $word->id); ?>
						@endforeach
						{!! implode(', ', $links) !!}
					</div>
				</div>
			@endif

			{!! Form::open(['route' => 'word_store_path', 'class' => 'form-horizontal']) !!}
			
				<div class="form-group {{ $errors->has('meaning_id') ? 'has-error' : '' }}">
					<label class="col-md-4 control-label">Word id</label>
					<div class="col-md-2">
						{!! Form::email('meaning_id', isset($meaning) ? $meaning->id : '', ['class' => 'form-control', 'id' => 'meaning_id']) !!}
						{!! $errors->first('meaning_id', '<span class="help-block">:message</span>') !!}
					</div>
					<div class="col-md-4">
						{!! Form::email('meaning_root', isset($meaning) ? $meaning->root : '', ['class' => 'form-control', 'disabled', 'id' => 'meaning_root']) !!}
					</div>
				</div>

				<div class="form-group {{ $errors->has('language_id') ? 'has-error' : '' }}">
					<label class="col-md-4 control-label">Language</label>
					<div class="col-md-6">
						{!! Form::select('language_id', $languages, NULL, ['class' => 'form-control', 'id' => 'type_selector']) !!}
						{!! $errors->first('language_id', '<span class="help-block">:message</span>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
					<label class="col-md-4 control-label">Translation text</label>
					<div class="col-md-6">
						{!! Form::email('text', NULL, ['class' => 'form-control']) !!}
						{!! $errors->first('text', '<span class="help-block">:message</span>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->has('comment') ? 'has-error' : '' }}">
					<label class="col-md-4 control-label">Comment</label>
					<div class="col-md-6">
						{!! Form::email('comment', NULL, ['class' => 'form-control']) !!}
						{!! $errors->first('comment', '<span class="help-block">:message</span>') !!}
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-6 col-md-offset-4">
						<button type="submit" class="btn btn-success">
							<span class="glyphicon glyphicon-plus-sign"></span> Create
						</button>
					</div>
				</div>
			{!! Form::close() !!}

			<button onclick="document.location='{{ route('search_path') }}'" type="submit" class="btn btn-primary">
				<span class="glyphicon glyphicon-search"></span> Goto search
			</button>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(function() 
		{
			var url = '{{ route('ajax_simple_meaning_path', [], false) }}';
			var _globalObj = <?= json_encode(array('_token'=> csrf_token())) ?>;
			var token = _globalObj._token;

			$('#meaning_id').bindWithDelay('input propertychange paste', function() 
			{
				$('#words').remove();
				meaning_id = $(this).val();
				setMeaningRoot(meaning_id, url, token);
			}, 200);

			var language_id = {{ $language_id }};
			if (language_id)
			{
				$('#type_selector').val(language_id);
			}
		});

		function setMeaningRoot(meaning_id, url, token) 
		{
			$.ajax({
				type: 'POST',
				url: url,
				data: { meaning_id: meaning_id, _token: token },
				success: function(meaning) 
				{
					$('#meaning_root').val(meaning['root']);
					console.log(meaning['words']);

					links = '<p>Translations with the same word root:</p> ';
					for (var i = meaning['words'].length - 1; i >= 0; i--) 
					{
						links = links + '<a href="/words/' + meaning['words'][i].id + '/edit">' + meaning['words'][i].text + '</a>';

						if (i != 0)
							links = links + ', ';
					};

					words_container = $(document.createElement('div')).addClass('panel panel-default').attr('id', 'words');;
					words_container = words_container.append($(document.createElement('div')).addClass('panel-body').append(links));

					$('#main-body').prepend(words_container);
				}
			})
		}
	</script>
@endsection