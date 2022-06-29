<?php

namespace App\Http\Controllers;

use App\Http\{Requests\StoreMail,Resources\Mail\MailResource,Services\Email\EmailService};
use App\Models\Mail;
use Illuminate\Http\{JsonResponse,Request};


class MailController extends Controller
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Get all mails and group them by status
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->successResponse(MailResource::collection(Mail::where('user_id', auth()->user()->id)->paginate()));
    }

    /**
     * Get a mail by id
     *
     * @param string $uuid
     * @return JsonResponse
     */
    public function show(string $uuid): JsonResponse
    {
        try {
            return $this->successResponse(new MailResource(Mail::where('user_id', auth()->user()->id)->where('uuid', $uuid)->firstOrFail()));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Create a mail
     *
     * @param StoreMail $request
     * @return JsonResponse
     */
    public function store(StoreMail $request): JsonResponse
    {
        try {
            $this->emailService->send(
                $request->from,
                $request->to,
                $request->subject,
                $request->text_content,
                $request->html_content,
                $request->attachments ?? []
            );
            return $this->successResponse([],'Your mail is being sent');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Search mails by parameters
     *
     * @param Request $request
     * @return JsonResponse
     *
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $mails = $this->emailService->search($request->all());
            return $this->successResponse(MailResource::collection($mails));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function getMailToken(): JsonResponse
    {
        try {
            return $this->successResponse($this->emailService->emailToken());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

}
