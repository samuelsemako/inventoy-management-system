<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
     public function toArray(Request $request): array
    {
        return [
            'customerId' => $this->customer_id,
            'firstName' => $this->first_name,
            'middleName' => $this->middle_name,
            'lastName' => $this->last_name,
            'emailAddress' => $this->email_address,
            'phoneNumber' => $this->phone_number,
            'homeAddress' => $this->home_address,
            'gender' => [
                'genderId' => $this->gender_id,
                'genderName' => $this->gender?->gender_name
            ],
            'title' => [
                'titleId' => $this->title_id,
                'titleName' => $this->title?->title_name
            ],
             'status' => [
                'statusId' => $this->status_id,
                'statusName' => $this->status?->status_name
            ]
        ];
    }
}
