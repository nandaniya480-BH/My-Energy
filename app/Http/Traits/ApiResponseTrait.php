<?php

namespace App\Http\Traits;

trait ApiResponseTrait
{
    public function sendResponse($message, $status, $result, $statusCode)
    {
        $response = [
            'message' => $message,
            'status' => $status,
            'data'    => $result,
        ];
        return response()->json($response, $statusCode);
    }

    public function sendError($error, $code, $errorMessages = [])
    {
        $response = [
            'message' => $error,
            'status' => false,
            'error_code' => $code,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
