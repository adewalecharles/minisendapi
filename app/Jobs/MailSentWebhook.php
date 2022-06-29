<?php

namespace App\Jobs;

use App\Models\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class MailSentWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $retryAfter = 10;

    protected int $retries = 3;

    protected Mail $mail;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // send webhook response if webhook is set
        $res = Http::post($this->mail->webhook_url, [
            'status' => true,
            'mail_id' => $this->mail->uuid,
            'message' => 'Mail sent successfully',
        ]);

        // resend job if webhook response is not 200
        if (!$res->ok()) {
            $this->release($this->retryAfter);
        }
    }
}
