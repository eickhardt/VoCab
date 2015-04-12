@extends('app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>
				<span class="glyphicon glyphicon-stats"></span> Statistics
			</h2>
		</div>

		<div class="panel-body">

			<h3>Recently added words count</h3>

			<div class="panel panel-default">
				<div class="table-responsive"> 
					<table class="table table-hover table-bordered table-striped">
						<thead>
							<tr class="active">
								<th></th>
								<th>{{ date('Y-m-d', strtotime('-6 day', time())) }}</th>
								<th>{{ date('Y-m-d', strtotime('-5 day', time())) }}</th>
								<th>{{ date('Y-m-d', strtotime('-4 day', time())) }}</th>
								<th>{{ date('Y-m-d', strtotime('-3 day', time())) }}</th>
								<th>{{ date('Y-m-d', strtotime('-2 day', time())) }}</th>
								<th>Yesterday</th>
								<th>Today</th>
							</tr>
						</thead>
						@foreach ($recent_words_data as $key => $fields)
							<tr>
								<td class="active"><b>{{ $key }}</b></td>
								@foreach ($fields as $field)
								<td>
									{{ $field }}
								</td>
								@endforeach
							</tr>
						@endforeach
					</table>
				</div>
			</div>

			<?php /*
			<h3>General statistics</h3>

			<div class="panel panel-default">
				<div class="table-responsive"> 
					<table class="table table-hover table-bordered table-striped">
						<thead>
							<tr class="active">
								<th></th>
								<th>Total</th>
								@foreach ($types as $type)
									<th>{{ $type->name }}s</th>
								@endforeach
								<th>Total %</th>
							</tr>
						</thead>
						@foreach ($statistics_data as $fields)
							<?php $count = 0; ?>
							<tr>
								@foreach ($fields as $field)
									@if ($count == 0)
										<td class="active"><b>{{ $field }}</b></td>
									@else
										<td>{{ $field }}</td>
									@endif
									<?php $count++; ?>
								@endforeach
							</tr>
						@endforeach
					</table>
				</div>
			</div>
			*/ ?>
		</div>
	</div>
@endsection
