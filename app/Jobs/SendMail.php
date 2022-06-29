<?php

namespace App\Jobs;

use App\Models\Mail;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMail implements ShouldQueue
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
    public function handle(): void
    {
        // try to send mail with SwiftMailer
        try {
            \Illuminate\Support\Facades\Mail::to($this->mail->to)->queue(new \App\Mail\SendMail($this->mail));

            // update mail status to sent and dispatch a new job to send webhook if webhook is set
            $this->mail->statuses()->create([
                'status' => Mail::STATUS_SENT,
            ]);

            MailSentWebhook::dispatchIf($this->mail->webhook_url, $this->mail);
        } catch (Exception $e) {
            // if mail sending fails, retry it
            $this->release($this->retryAfter);
        }
    }

    public function failed(Exception $exception)
    {
        // update mail status to failed and dispatch webhook if webhook_url is set
        $this->mail->statuses()->create([
            'status' => Mail::STATUS_FAILED,
        ]);
        MailFailedWebhook::dispatchIf($this->mail->webhook_url, $this->mail, $exception);
    }
}
