<?php

namespace App\Exceptions\Api;

class ShiftInUseException extends ApiException
{
    protected int $statusCode = 422;
    protected string $messageKey = 'messages.shift.delete_forbidden';
}
