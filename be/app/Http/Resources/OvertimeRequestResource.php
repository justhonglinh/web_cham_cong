<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OvertimeRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'status'         => $this->status,
            'created_at'     => $this->created_at,
            'user'           => new UserResource($this->whenLoaded('user')),
            'overtime_shift' => new OvertimeShiftResource($this->whenLoaded('overtimeShift')),
        ];
    }
}
