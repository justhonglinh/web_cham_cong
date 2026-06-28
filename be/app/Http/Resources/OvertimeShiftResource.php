<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OvertimeShiftResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'name'                   => $this->name,
            'date'                   => $this->date,
            'start_time'             => $this->start_time,
            'end_time'               => $this->end_time,
            'max_registrations'      => $this->max_registrations,
            'overtime_requests_count' => $this->whenCounted('overtimeRequests'),
        ];
    }
}
