@extends('app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>
                <span class="glyphicon glyphicon-stats"></span> Statistics
            </h2>
        </div>

        <div class="panel-body">

            <h3>Contributions</h3>
            <div class="panel panel-default">
                <div class="panel-body">


                    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
                    <script type="text/javascript" src="//d3js.org/d3.v3.min.js"></script>
                    <script type="text/javascript"
                            src="//cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.min.js"></script>
                    <link rel="stylesheet" href="//cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.css"/>

                    @foreach ($contribution_calendar_years as $year)
                        <div class="table-responsive">
                            <div id="cal-heatmap-{{ $year }}"></div>
                        </div>
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
                                legend: [20, 50, 100, 200],
                                label: {
                                    position: "top",
                                    align: "left"
                                },
                                legendColors: {
                                    empty: "#efefef",
                                    base: "white",
                                    min: "#dff0d8",
                                    max: "#216e39",
                                },
                                @if($loop->last)
                                displayLegend: true,
                                @else
                                displayLegend: false,
                                @endif
                            });
                        </script>
                    @endforeach
                </div>
            </div>

            <h3>Recent activity</h3>
            <div class="panel panel-default">
                <div class="panel-body">


                    <script type="text/javascript"
                            src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
                    <script type="text/javascript" src="/js/palette.js">

                    </script>

                    <div style="height: 200px">
                        <canvas id="recentWords" width="400" height="400"></canvas>
                    </div>
                    <script>
                        function hexToRGB(hex, alpha) {
                            var r = parseInt(hex.slice(1, 3), 16),
                                g = parseInt(hex.slice(3, 5), 16),
                                b = parseInt(hex.slice(5, 7), 16);

                            if (alpha) {
                                return "rgba(" + r + ", " + g + ", " + b + ", " + alpha + ")";
                            } else {
                                return "rgb(" + r + ", " + g + ", " + b + ")";
                            }
                        }

                        let ctx = document.getElementById('recentWords').getContext('2d');
                        let myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: ['{{ date('Y-m-d', strtotime('-6 day', time())) }}', '{{ date('Y-m-d', strtotime('-5 day', time())) }}', '{{ date('Y-m-d', strtotime('-4 day', time())) }}', '{{ date('Y-m-d', strtotime('-3 day', time())) }}', '{{ date('Y-m-d', strtotime('-2 day', time())) }}', 'Yesterday', 'Today'],
                                datasets: [

                                        @foreach ($recent_words_data as $key => $fields)
                                    {
                                        label: '{{ $key }}',
                                        backgroundColor: palette(['tol'], {{ count($recent_words_data) }}, {{ $loop->index }}).map(function (hex) {
                                            return hexToRGB('#' + hex, 0.2);
                                        })[{{ $loop->index }}],
                                        borderColor: palette(['tol'], {{ count($recent_words_data) }}, {{ $loop->index }}).map(function (hex) {
                                            return '#' + hex;
                                        })[{{ $loop->index }}],
                                        borderWidth: 1,
                                        data: [
                                            @foreach ($fields as $field)
                                            {{ $field }},
                                            @endforeach
                                        ],
                                    },
                                    @endforeach
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                </div>
            </div>

            <h3>Distribution</h3>
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

        </div>
    </div>
@endsection
