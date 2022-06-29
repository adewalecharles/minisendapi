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
use Illuminate\Support\Facades\Http;

class MailFailedWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $retryAfter = 10;

    protected int $retries = 3;

    protected Mail $mail;

    protected Exception $exception;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mail $mail, Exception $exception)
    {
        $this->mail = $mail;
        $this->exception = $exception;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        // send webhook response with failed status
        $res = Http::post($this->mail->webhook_url, [
            'status' => false,
            'reason' => $this->exception->getMessage(),
        ]);

        // if webhook response is not 200, retry it
        if (!$res->ok()) {
            $this->release($this->retryAfter);
        }

    }

    public function failed(Exception $exception)
    {
        // we can now have a notification channel maybe a teams channel or something else to let us know that the webhook failed
    }
}
