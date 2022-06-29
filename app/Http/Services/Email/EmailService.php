<?php
namespace App\Http\Services\Email;

use App\{Http\Services\Email\EmailServiceInterface, Jobs\SendMail, Models\Mail};
use Laravel\Sanctum\PersonalAccessToken;

class EmailService implements EmailServiceInterface
{

    /**
     * Send a mail
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $text_content
     * @param string $html_content
     * @param array|null $attachments
     * @param string|null $webhook_url
     * @return void
     */
    public function send(string $from, string $to, string $subject, string $text_content, string $html_content, array $attachments = [], string $webhook_url = null): void
    {
        // Create a mail
        $mail = new Mail();
        $mail->from = $from;
        $mail->to = $to;
        $mail->subject = $subject;
        $mail->text_content = $text_content;
        $mail->html_content = $html_content;
        $mail->attachments = empty($attachments) ? [] : $attachments;
        $mail->user_id = auth()->user()->id;
        $mail->webhook_url = empty($webhook_url) ? null : $webhook_url;
        $mail->save();

        // create a posted status for the mail
        $mail->statuses()->create([
            'name' => Mail::STATUS_POSTED,
        ]);
        // dispatch a job to send the mail
        SendMail::dispatch($mail);
    }

    /**
     * Get all mails based on search criteria
     * @param array $data
     * @return Mail
     */
    public function search(array $data): Mail
    {
       return Mail::when($data['from'], function($query) use ($data) {
            $query->where('from', $data['from']);
        })->when($data['to'], function($query) use ($data) {
            $query->whereJsonContains('to', $data['to']);
        })->when($data['subject'], function($query) use ($data) {
            $query->where('subject', 'LIKE', "%{$data['subject']}%");
        })->where('user_id', auth()->user()->id)->get();
    }

    /**
     * Function get token that has abilities to send mail
     */
    public function emailToken():PersonalAccessToken
    {
      return auth()->user()->tokens()->where('name', 'email')->first();
    }

    /**
     * Get all mails
     *
     * @param string $uuid
     * @return Mail
     */
    public function getMail(string $uuid):Mail
    {
        return Mail::where('uuid', $uuid)->first();
    }

}
