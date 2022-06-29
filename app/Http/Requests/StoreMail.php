<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMail extends FormRequest
{

    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'from' => 'required|email',
            'to' => 'required|email',
            'subject' => 'required|string',
            'text_content' => 'required|string',
            'html_content' => 'required|string',
            'attachments' => 'sometimes|nullable|array',
            'attachments.*' => 'nullable|file',
            'webhook_url' => 'sometimes|nullable|url',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'from.required' => 'The "from" field is required.',
            'from.email' => 'The "from" field must be a valid email address.',
            'to.required' => 'The "to" field is required.',
            'to.*.required' => 'The "to" field is required.',
            'to.*.email' => 'The "to" field must be a valid email address.',
            'subject.required' => 'The "subject" field is required.',
            'text_content.required' => 'The "text_content" field is required.',
            'html_content.required' => 'The "html_content" field is required.',
            'attachments.required' => 'The "attachments" field is required.',
            'attachments.*.required' => 'The "attachments" field is required.',
            'attachments.*.file' => 'The "attachments" field must be a file.',
            'webhook_url.url' => 'The "webhook_url" field must be a valid url.',
        ];
    }
}
