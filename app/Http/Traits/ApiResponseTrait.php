<?php

namespace App\Http\Traits;

trait ApiResponseTrait
{
    public function success($message, $data, $statusCode)
    {
        $response = [
            'message' => $message,
            'status' => 'Success',
            'data'    => $data,
        ];
        return response()->json($response, $statusCode);
    }

    public function error($error, $statusCode, $errorData = [])
    {
        $response = [
            'message' => $error,
            'status' => 'Error',
            'error_code' => $statusCode,
        ];

        if (!empty($errorData)) {
            $response['errors'] = $errorData;
        }
        return response()->json($response, $statusCode);
    }
}
