<?php

namespace App\Exceptions\Api;

class OvertimeAlreadyRegisteredException extends ApiException
{
    protected int $statusCode = 422;
    protected string $messageKey = 'messages.overtime.already_registered';
}
