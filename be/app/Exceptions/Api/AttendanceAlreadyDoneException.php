<?php

namespace App\Exceptions\Api;

class AttendanceAlreadyDoneException extends ApiException
{
    protected int $statusCode = 422;
    protected string $messageKey = 'messages.attendance.already_done';
}
