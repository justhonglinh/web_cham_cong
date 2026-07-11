<?php

namespace App\Exceptions\Api;

class OutsideCheckInRadiusException extends ApiException
{
    protected int $statusCode = 422;
    protected string $messageKey = 'messages.attendance.outside_radius';
}
