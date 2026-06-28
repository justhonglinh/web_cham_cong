<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'leave_type'  => $this->leave_type,
            'start_date'  => $this->start_date?->format('Y-m-d'),
            'end_date'    => $this->end_date?->format('Y-m-d'),
            'reason'      => $this->reason,
            'status'      => $this->status,
            'approved_at' => $this->approved_at,
            'rejected_at' => $this->rejected_at,
            'created_at'  => $this->created_at,
            'user'        => new UserResource($this->whenLoaded('user')),
        ];
    }
}
