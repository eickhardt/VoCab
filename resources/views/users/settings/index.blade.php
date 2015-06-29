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
				<h4>Languages</h4>
				<p>These languages will be used by default when searching.</p>
				<div class="well well-sm language_well">
					@foreach ($languages as $language)
						<span class="search_language">
							<img src="{{ $language->image }}"> 
							{!! Form::checkbox($language->short_name, $language->id, !in_array($language->id, $user_languages), ['class' => 'language_checkbox']) !!}
						</span>
					@endforeach
				</div>
				<br>
				<button type="submit" class="btn btn-success">
					<span class="glyphicon glyphicon-ok"></span> Update
				</button>
			{!! Form::close() !!}
			
		</div>
	</div> 
@endsection

@section('scripts')
@endsection