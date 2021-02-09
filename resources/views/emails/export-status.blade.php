@if ($success)
Your data has been exported and is <a href="{{ route('csv_export_download_path') }}">ready for download</a>.

It will be available for {{ $hours_available }} hours, or until you create another which will automatically replace it.
@else
An error occurred while processing your data export. Please try again or contact an admin if the problem persists.
@endif

Export ID: {{ $export_id }}
