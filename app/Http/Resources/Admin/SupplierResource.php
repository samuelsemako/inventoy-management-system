<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'supplierId' => $this->supplier_id,
            'supplierName' => $this->supplier_name,
            'emailAddress' => $this->email_address,
            'phoneNumber' => $this->phone_number,
            'supplierAddress' => $this->supplier_address,
            'statusId' => $this->status_id,
            'statusName' => $this->status?->status_name,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
