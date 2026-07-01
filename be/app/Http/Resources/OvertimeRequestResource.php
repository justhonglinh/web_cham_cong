<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OvertimeRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'employee_name' => $this->whenLoaded('user', fn() => $this->user?->name),
            'shift_name'    => $this->whenLoaded('overtimeShift', fn() => $this->overtimeShift?->name),
            'shift_date'    => $this->whenLoaded('overtimeShift', fn() => $this->overtimeShift?->date?->format('Y-m-d')),
            'status'        => $this->status,
        ];
    }
}
