<?php

namespace App\Jobs;

use App\Mail\CsvExportStatusMail;
use App\Mail\CsvExportStatusMailMarkdown;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;
use Mail;

class SendCsvExportStatusEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User The user to send that status email to.
     */
    protected $user;

    /**
     * @var bool Whether or not the export was successful.
     */
    protected $success;

    /**
     * @var string Unique id of the export.
     */
    protected $export_id;

    /**
     * Create a new job instance.
     *
     * @param User $user The user to send that status email to.
     * @param bool $success Whether or not the export was successful.
     * @param string $export_id Unique id of the export.
     */
    public function __construct(User $user, $success, $export_id)
    {
        $this->user      = $user;
        $this->success   = $success;
        $this->export_id = $export_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info(self::class . ' is being handled', [
            'user_id'   => $this->user->id,
            'export_id' => $this->export_id
        ]);

        $mail = new CsvExportStatusMailMarkdown(
            $this->success,
            $this->export_id
        );

        Mail::to($this->user->email)
            ->send($mail);
    }
}
