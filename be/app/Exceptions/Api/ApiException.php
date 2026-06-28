<?php

namespace App\Exceptions\Api;

use Illuminate\Http\JsonResponse;
use RuntimeException;

abstract class ApiException extends RuntimeException
{
    protected int $statusCode = 422;
    protected string $messageKey = '';

    public function render(): JsonResponse
    {
        return response()->json(
            ['message' => __($this->messageKey)],
            $this->statusCode
        );
    }
}
