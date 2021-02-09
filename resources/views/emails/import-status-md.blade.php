@component('mail::message')
# Vokapp import

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

@if ($success)

@component('mail::button', ['url' => route('statistics_path')])
See the result
@endcomponent

@else

@component('mail::button', ['url' => route('import_path')])
Retry
@endcomponent

@endif

Import ID: {{ $import_id }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
