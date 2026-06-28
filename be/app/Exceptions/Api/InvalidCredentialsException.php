<?php

namespace App\Exceptions\Api;

class InvalidCredentialsException extends ApiException
{
    protected int $statusCode = 401;
    protected string $messageKey = 'messages.auth.invalid_credentials';
}
