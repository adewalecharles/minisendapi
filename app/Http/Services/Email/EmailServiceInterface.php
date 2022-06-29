<?php

namespace App\Http\Services\Email;

use App\Models\Mail;
use Laravel\Sanctum\PersonalAccessToken;

interface EmailServiceInterface
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
    public function send(string $from, string $to, string $subject, string $text_content, string $html_content, array $attachments = [], string $webhook_url = null): void;

    /**
     * Get all mails based on search criteria
     * @param array $data
     * @return Mail
     */
    public function search(array $data): Mail;

    /**
     * Function get token that has abilities to send mail
     */
    public function emailToken(): PersonalAccessToken;

    /**
     * Get all mails
     *
     * @param string $uuid
     * @return Mail
     */
    public function getMail(string $uuid): Mail;

}
