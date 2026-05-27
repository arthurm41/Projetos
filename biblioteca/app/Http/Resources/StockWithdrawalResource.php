<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockWithdrawalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'quantity'     => $this->quantity,
            'stock_before' => $this->stock_before,
            'stock_after'  => $this->stock_after,
            'class_group'  => $this->class_group,
            'reason'       => $this->reason,
            'withdrawn_at' => $this->withdrawn_at?->toDateTimeString(),
            'book'         => new BookResource($this->whenLoaded('book')),
            'registered_by' => $this->whenLoaded('user', fn () => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ]),
            'created_at'   => $this->created_at?->toDateTimeString(),
        ];
    }
}
