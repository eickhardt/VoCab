@extends('app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>
				@if (isset($list_type) && $list_type == 'Random')
					<span class="glyphicon glyphicon-question-sign"></span>
				@elseif (isset($list_type) && $list_type == 'Trashed')
					<span class="glyphicon glyphicon-trash"></span>
				@elseif (isset($list_type) && $list_type == 'Recent')
					<span class="glyphicon glyphicon-star"></span>
				@else
					<span class="glyphicon glyphicon-list-alt"></span>
				@endif
				Translations / List / <b>{{ isset($list_type) ? $list_type : $words[0]->text }}</b>
			</h2>
		</div>
		<div class="panel-body">
		
			@if (isset($list_type) && $list_type == 'Recent')
				{!! $words->render() !!} <br><br>
			@endif()
			
			@forelse ($words as $word)
				<div class="panel panel-default">
					<div class="panel-body">

						{{-- <h4>Translation information</h4> --}}
						<div class="row">
							<div class="col-md-6 col-md-offset-0">
								<ul class="list-group no-bot-margin">
									<li class="list-group-item">
										<img src="{{ $word->language->image }}">
										<b>{!! link_to_route('meaning_edit_path', $word->text, $word->meaning_id) !!}</b>
										@if ($word->deleted_at)
											<a href="{{ route('word_restore_path', $word->id) }}" type="submit" class="btn btn-xs btn-success pull-right">
												<span class="glyphicon glyphicon-refresh"></span> Restore word
											</a>
										@else
											<a href="{{ route('word_edit_path', $word->id) }}" type="submit" class="btn btn-xs btn-success pull-right">
												<span class="glyphicon glyphicon glyphicon-pencil"></span> Edit word
											</a>
										@endif
									</li>
									{{-- <li class="list-group-item">
										Language: <img src="{{ $word->language->image }}"> {{ $word->language->name }}
									</li> --}}
								{{-- </ul>
							</div>
						
							<div class="col-md-6 col-md-offset-0">
								<ul class="list-group">
									<li class="list-group-item">
										Created at {{ date("F j, Y, g:i a", strtotime($word->created_at)) }}
									</li> --}}
									<li class="list-group-item">
										Last updated at {{ date("F j, Y, g:i a", strtotime($word->updated_at)) }}
									</li>
								</ul>
							</div>
						</div>

						{{-- @if ($word->deleted_at)
							<a href="{{ route('word_restore_path', $word->id) }}" type="submit" class="btn btn-success">
								<span class="glyphicon glyphicon-refresh"></span> Restore translation
							</a>
						@else
							<a href="{{ route('word_edit_path', $word->id) }}" type="submit" class="btn btn-success">
								<span class="glyphicon glyphicon glyphicon-pencil"></span> Edit translation
							</a>
						@endif --}}

						{{-- <br> --}}
					</div>
				</div>

			@empty
				<ul class="list-group">
					<li class="list-group-item">There seems to be nothing here.</li>
				</ul>
			@endforelse
			
			@if (isset($list_type) && $list_type == 'Random')
				<a href="{{ route('word_random_path') }}" type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-question-sign"></span> Another one
				</a>
			@endif

			@if (isset($list_type) && $list_type == 'Recent')
				{!! $words->render() !!} <br><br>
			@endif()

			<a href="{{ route('search_path') }}" type="submit" class="btn btn-primary">
				<span class="glyphicon glyphicon-search"></span> Goto search
			</a>
		</div>
	</div>
@endsection
