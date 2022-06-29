<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function errorResponse($message, $status = 400): JsonResponse
    {
        return response()->json(['status' => false, 'message' => $message], $status);
    }

    public function successResponse($data = [], $message = 'success', $status = 200): JsonResponse
    {
        return response()->json(['status' => true, 'message' => $message, 'data' => $data], $status);
    }
}
