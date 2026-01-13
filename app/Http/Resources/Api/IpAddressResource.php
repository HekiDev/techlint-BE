<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IpAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        parent::toArray($request);

        return [
            'id' => $this->id,
            'address' => $this->address,
            'label' => $this->label,
            'comment' => $this->comment,
        ];
    }
}
