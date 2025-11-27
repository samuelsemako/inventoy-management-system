<?php

namespace App\Http\Resources\admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'SalesId' => $this->sales_id,
            'totalAmount' => $this->total_amount,
            'customer' => [
                'customerId' => $this->customer_id,
                'customerName' => $this->customer?->first_name . ' ' . $this->customer?->middle_name . ' ' . $this->customer?->last_name,
            ],
            'paymentMethod' => [
                'paymentMethodId' => $this->payment_method_id,
                'paymentMethodName' => $this->payment_method?->payment_method_name
            ],
            'soldBy' => [
                'adminId' => $this->seller->admin_id,
                'adminName' => $this->seller?->first_name . ' ' . $this->seller?->middle_name . ' ' . $this->seller?->last_name,
            ],
            'items' => $this->items->map(function ($item) {
                return [
                    'saleItemId' => $item->sales_id,
                    'product' => [
                        'productId' => $item->product_id,
                        'productName' => $item->product?->product_name,
                        'price' => $item->unit_price,
                    ],
                    'quantity' => $item->quantity,
                    'total' => $item->sub_total,
                ];
            }),
            'createdAt' => $this->created_at->format('d M Y, h:i A'),
        ];
    }
}
