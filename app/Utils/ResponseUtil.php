<?php

namespace App\Utils;

trait ResponseUtil
{
    private function create($message, $code, $data = null)
    {
        $response = [
            'status' => $code,
            'message' => $message,
        ];

        if ($data != null) {
            $response['data'] = $data;
        }

        return $response;
    }

    public function sendSuccess($result = null, $message = 'Success', $code = 200)
    {
        return response()->json(
            $this->create($message, $code, $result),
            $code
        );
    }

    public function sendError($error, $code = 500)
    {
        return response()->json(
            $this->create($error, $code),
            $code
        );
    }
}
