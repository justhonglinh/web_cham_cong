<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'date'          => $this->date,
            'check_in'      => $this->check_in_time,
            'check_out'     => $this->check_out_time,
            'status'        => $this->status,
            'shift_name'    => $this->whenLoaded('shift', fn() => $this->shift?->name),
            'employee_name' => $this->whenLoaded('user', fn() => $this->user?->name),
            'employee_id'   => $this->whenLoaded('user', fn() => $this->user?->id),
        ];
    }
}
