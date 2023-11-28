<?php

namespace App\Http\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

trait ApiResponseTrait
{
    /**
     * Core of response
     * 
     * @param   string          $message
     * @param   array|object    $data
     * @param   integer         $statusCode  
     * @param   boolean         $isSuccess
     */
    public static function apiResponse($message, $data = [], $statusCode, $isSuccess = true)
    {
        // Check the params
        if (!$message) return response()->json(['message' => 'Message is required'], 500);

        // Send the response
        if ($isSuccess) {
            return response()->json([
                'message' => $message,
                'error' => false,
                'code' => $statusCode,
                'results' => $data
            ], is_int($statusCode) && $statusCode != 0 ? $statusCode : 400);
        } else {
            return response()->json([
                'message' => $message,
                'error' => true,
                'code' => $statusCode,
                'errors' => $data
            ], is_int($statusCode) && $statusCode != 0 ? $statusCode : Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Send any success response
     * 
     * @param   string          $message
     * @param   array|object    $data
     * @param   integer         $statusCode
     */
    public static function success($message, $data = [], $statusCode = Response::HTTP_OK)
    {
        return Self::apiResponse($message, $data, $statusCode);
    }

    /**
     * Send any error response
     * 
     * @param   string          $message
     * @param   integer         $statusCode    
     */
    public static function error($message, $data = [], $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return Self::apiResponse($message, $data, $statusCode, false);
    }
}