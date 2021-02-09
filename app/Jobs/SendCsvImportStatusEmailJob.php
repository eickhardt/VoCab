<?php

namespace App\Jobs;

use App\Mail\CsvImportStatusMailMarkdown;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;
use Mail;

class SendCsvImportStatusEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var bool Whether or not the import was successful.
     */
    protected $success;

    /**
     * @var User The user to send that status email to.
     */
    protected $user;

    /**
     * @var string|null Message for the user.
     */
    protected $message;

    /**
     * @var string Id of the import.
     */
    protected $import_id;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param bool $success
     * @param string $message
     * @param string $import_id
     */
    public function __construct(User $user, $success, $message, $import_id)
    {
        $this->user      = $user;
        $this->success   = $success;
        $this->message   = $message;
        $this->import_id = $import_id;
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
            'import_id' => $this->import_id
        ]);

        $mail = new CsvImportStatusMailMarkdown(
            $this->success,
            $this->import_id,
            $this->message
        );

        Mail::to($this->user->email)
            ->send($mail);
    }
}
