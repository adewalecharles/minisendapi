<?php

namespace App\Http\Resources\Mail;

use Illuminate\Http\Resources\Json\JsonResource;

class MailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->uuid,
            'from' => $this->from,
            'to' => $this->to,
            'subject' => $this->subject,
            'text' => $this->text_content,
            'html' => $this->html_content,
            'attachments' => $this->attachments,
            'status' => StatusResource::collection($this->statuses),
        ];
    }
}
