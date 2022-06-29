<?php

namespace App\Mail;

use App\Models\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public Mail $mail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.default')->with([
            'text' => $this->mail->text_content])
            ->from($this->mail->from, $this->mail->user->username)
            ->subject($this->mail->subject)
            ->html($this->mail->html_content)
            ->buildAttachments($this->mail->attachments);
    }
}
