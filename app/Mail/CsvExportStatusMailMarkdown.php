<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CsvExportStatusMailMarkdown extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var bool Whether or not the export was successful.
     */
    public $success;

    /**
     * @var string The id of the export.
     */
    public $export_id;

    /**
     * @var string How many hours the generated CSV file is available for download.
     */
    public $hours_available;

    /**
     * Create a new message instance.
     *
     * @param bool $success Whether or not the export was successful.
     * @param string $export_id The id of the export.
     */
    public function __construct($success, $export_id)
    {
        $this->success         = $success;
        $this->export_id       = $export_id;
        $this->hours_available = config('app.hours_to_keep_csv_export_files');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.export-status-md')
                    ->subject(config('app.name') . ' CSV export');
    }
}
