<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_phone' => $this->customer_phone,
            'verification_code' => $this->when($request->user()?->role === 'admin', $this->verification_code),
            'company' => [
                'id' => $this->company->id,
                'name' => $this->company->name,
            ],
            'manager' => [
                'id' => $this->manager->id,
                'name' => $this->manager->name,
            ],
            'discount' => $this->discount,
            'usage_count' => $this->usage_count,
            'status' => $this->status,
            'verified_at' => $this->verified_at?->format('Y-m-d H:i:s'),
            'expires_at' => $this->expires_at?->format('Y-m-d H:i:s'),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
