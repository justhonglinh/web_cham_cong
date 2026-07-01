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
            'date'                   => $this->date?->format('Y-m-d'),
            'start_time'             => $this->start_time,
            'end_time'               => $this->end_time,
            'max_registrations'      => $this->max_registrations,
            'registration_count'     => $this->registration_count ?? 0,
            'is_registered'          => $this->is_registered ?? false,
        ];
    }
}
