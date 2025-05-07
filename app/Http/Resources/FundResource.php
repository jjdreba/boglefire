<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FundResource extends JsonResource
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
            'symbol' => $this->symbol,
            'name' => $this->name,
            'type' => $this->type,
            'exchange' => $this->exchange,
            'currency' => $this->currency,
            'last_price' => $this->last_price,
            'last_price_updated_at' => $this->last_price_updated_at?->diffForHumans(),
            'last_price_formatted' => $this->currency.' '.number_format($this->last_price, 2),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
