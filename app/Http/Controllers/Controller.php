<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @param $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse($data, $code = 200)
    {
        return response()->json($data, $code);
    }

    /**
     * @param string|null $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse(string $message = null, int $code = 400)
    {
        $return['success'] = false;
        $return['message'] = $message;

        return response()->json($return, $code);
    }

    /**
     * @param string|null $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse(string $message = null, $data = [])
    {
        $return['success'] = true;

        $message ? $return['message'] = $message : null;

        $data ? $return['data'] = $data : null;

        return response()->json($return);
    }
}
