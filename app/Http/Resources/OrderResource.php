<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'symbol' => $this->symbol,
            'type' => $this->type,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'duration' => $this->duration,
            'trailing_offset' => $this->trailing_offset,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
