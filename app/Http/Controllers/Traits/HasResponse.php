<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\ResourceResponse;

trait HasResponse
{
    /**
     * Sending success response with status code in json format.
     *
     * @param $result
     * @param int $code
     * @param Request|null $request
     * @return JsonResponse
     */
    public function sendResponse($result, int $code = 200, ?Request $request = null): JsonResponse
    {
        return ($result instanceof ResourceCollection) ?
            $result->toResponse($request ?? request()) :
            response()->json($result, $code);
    }

    /**
     * Sending error response in json format.
     *
     * @param $errors
     * @param int $errorCode
     * @return JsonResponse
     */
    public function sendError($errors, int $errorCode): JsonResponse
    {
        return response()->json(['errors' => (array) $errors], $errorCode);
    }
}
