<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    /**
     * Retorna uma resposta de sucesso.
     *
     * @param  mixed  $data
     * @param  string|null  $message
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = null, $message = null, $status = Response::HTTP_OK)
    {
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response, $status);
    }

    /**
     * Retorna uma resposta de erro.
     *
     * @param  mixed  $data
     * @param  string|null  $message
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($data = null, $message = null, $status = Response::HTTP_BAD_REQUEST)
    {
        $response = [
            'success' => false,
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response, $status);
    }
}
