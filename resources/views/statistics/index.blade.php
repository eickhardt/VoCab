@extends('app')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h2>
				<span class="glyphicon glyphicon-stats"></span> Statistics
			</h2>
		</div>

		<div class="panel-body">

			<h3>Contributions yearly</h3>

			<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
			<script type="text/javascript" src="//d3js.org/d3.v3.min.js"></script>
			<script type="text/javascript" src="//cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.min.js"></script>
			<link rel="stylesheet" href="//cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.css" />

			@foreach ($contribution_calendar_years as $year)		
				<div id="cal-heatmap-{{ $year }}"></div>
				<script type="text/javascript">
						var cal = new CalHeatMap();
						cal.init({
							itemSelector: "#cal-heatmap-{{ $year }}",
							domain: "year",
							subDomain: "day",
							data: "{{ route('contribution_calendar_data', $year) }}",
							start: new Date({{ $year }}, 0),
							cellSize: 10,
							range: 1,
							legend: [20, 40, 60, 80],
							@if($loop->last)
								displayLegend: true,
							@else
								displayLegend: false,
							@endif
						});
				</script>
			@endforeach


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
						@foreach ($statistics_data as $key => $fields)
							<tr>
								<td class="active">{{ $key }}</td>
								@foreach ($fields as $field)
									<td>{{ $field }}</td>
								@endforeach
							</tr>
						@endforeach
					</table>
				</div>
				
			</div>



			
			<?php /*<h3>General statistics</h3>

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
				
			</div> */ ?>
			
		</div>
	</div>
@endsection
