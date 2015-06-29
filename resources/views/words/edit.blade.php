@extends('app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>
				<span class="glyphicon glyphicon glyphicon-pencil"></span> Translations / Edit / <b>{{ $word->text }}</b>
			</h2>
		</div>

		<div class="panel-body">
			{!! Form::model($word, ['route' => ['word_update_path', $word->id], 'method' => 'PATCH', 'class' => 'form-horizontal']) !!}

				<div class="form-group {{ $errors->has('meaning_id') ? 'has-error' : '' }}">
					<label class="col-md-4 control-label">Word id</label>
					<div class="col-md-2">
						{!! Form::text('meaning_id', $word->meaning_id, ['class' => 'form-control', 'id' => 'meaning_id']) !!}
						{!! $errors->first('meaning_id', '<span class="help-block">:message</span>') !!}
					</div>
					<div class="col-md-4">
						{!! Form::text('meaning_root', isset($meaning) ? $meaning->root : '', ['class' => 'form-control', 'disabled', 'id' => 'meaning_root']) !!}
					</div>
				</div>

				<div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
					<label class="col-md-4 control-label">Text</label>
					<div class="col-md-6">
						{!! Form::text('text', NULL, ['class' => 'form-control']) !!}
						{!! $errors->first('text', '<span class="help-block">:message</span>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->has('language_id') ? 'has-error' : '' }}">
					<label class="col-md-4 control-label">Language</label>
					<div class="col-md-6">
						{!! Form::select('language_id', $languages, NULL, ['class' => 'form-control', 'id' => 'type_selector']) !!}
						{!! $errors->first('language_id', '<span class="help-block">:message</span>') !!}
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
							<span class="glyphicon glyphicon-ok-sign"></span> Update
						</button>
					</div>
				</div>
			{!! Form::close() !!}

			{!! Form::open(['route' => ['word_delete_path', $word->id], 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'delete_word_form']) !!}
				<div class="form-group">
					<div class="col-md-6 col-md-offset-4">
						<button id="delete_word_btn" type="submit" class="btn btn-primary btn-danger">
							<span class="glyphicon glyphicon-trash"></span> Trash
						</button>
					</div>
				</div>
			{!! Form::close() !!}

			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-md-6 col-md-offset-4">
						<a href="{{ route('meaning_path', $word->meaning_id) }}" class="btn btn-primary">
							<span class="glyphicon glyphicon-tree-conifer"></span> Root word
						</a>
					</div>
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
		$(function() 
		{
			$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

			var focus = '{{ Input::get('focus') }}';

			if (focus)
			{
				$( '.' + focus ).focus();
			}

			$( "#delete_word_btn" ).on('click', function(e) 
			{
				$(this).button("disable");
				if (confirm("Are you sure you want to trash this word?"))
				{
					$('#delete_word_form').submit();
				}
				else 
				{
					$(this).button("enable");
				}
				e.preventDefault();
			});

			var url = '<?= route('ajax_simple_meaning_path', [], false) ?>';
			var _globalObj = <?= json_encode(array('_token'=> csrf_token())) ?>;
			var token = _globalObj._token;

			setMeaningRoot($('#meaning_id').val(), url, token);

			$('#meaning_id').bindWithDelay('input propertychange paste', function() 
			{
				meaning_id = $(this).val();
				setMeaningRoot(meaning_id, url, token);
			}, 200);
		});

		function setMeaningRoot(meaning_id, url, token) 
		{
			$.ajax({
				type: 'POST',
				url: url,
				data: { meaning_id: meaning_id, _token: token },
				success: function(meaning) {
					$('#meaning_root').val(meaning['root']);
				}
			})
		}
	</script>
@endsection