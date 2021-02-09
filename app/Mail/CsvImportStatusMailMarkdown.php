<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CsvImportStatusMailMarkdown extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var bool Whether or not the import was successful.
     */
    public $success;

    /**
     * @var string Id of the import.
     */
    public $import_id;

    /**
     * @var string|null Message for the user.
     */
    public $import_message;

    /**
     * Create a new message instance.
     *
     * @param bool $success Whether or not the import was successful.
     * @param string $import_id Id of the import.
     * @param string $import_message Message for the user.
     */
    public function __construct($success, $import_id, $import_message)
    {
        $this->success        = $success;
        $this->import_id      = $import_id;
        $this->import_message = $import_message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.import-status-md')
                    ->subject(config('app.name') . ' CSV import');
    }
}
