@if ($success)
{{ $import_message  }}

Have a look on the <a href="{{ route('statistics_path') }}">statistics</a> page.
@else
@if ($import_message)
{{ $import_message }}
@else
An unknown error occurred.
@endif

No data has been imported. Please <a href="{{ route('import_path') }}">try again</a> or contact an admin.
@endif

Import ID: {{ $import_id }}
