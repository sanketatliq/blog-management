<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponseHelper
{
    /**
     * Success Response message
     *
     * @param  string  $message
     * @param  object  $result
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($message, $result = '', $code = Response::HTTP_OK)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        $response['data'] = ! empty($result) ? $result : (object) [];

        return response()->json($response, $code);
    }

    /**
     * return error message
     *
     * @param  string  $error
     * @param  array  $errorMessages
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $code = Response::HTTP_UNAUTHORIZED, $th = null)
    {
        if (! empty($th)) {
            $error = config('app.env') === 'local' ? $th->getMessage() : $error;
        }

        $response = [
            'success' => false,
            'message' => $error,
        ];

        $response['data'] = (object) [];

        return response()->json($response, $code);
    }

    /**
     * Error with Data Response message
     *
     * @param  string  $message
     * @param  object  $result
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    public function sendErrorDataResponse($error, $code = Response::HTTP_UNAUTHORIZED, $result = '', $th = null)
    {
        if (! empty($th)) {
            $error = config('app.env') === 'local' ? $th->getMessage() : $error;
        }

        $response = [
            'success' => false,
            'message' => $error,
        ];

        $response['data'] = ! empty($result) ? $result : (object) [];

        return response()->json($response, $code);
    }
}
