<?php

namespace App\Exceptions\Api;

class InvalidResetTokenException extends ApiException
{
    protected int $statusCode = 422;
    protected string $messageKey = 'messages.auth.invalid_reset_token';
}
