<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'isbn'          => $this->isbn,
            'author'        => $this->author,
            'publisher'     => $this->publisher,
            'edition'       => $this->edition,
            'current_stock' => $this->current_stock,
            'minimum_stock' => $this->minimum_stock,
            'is_low_stock'  => $this->isLowStock(),
            'subject'       => new SubjectResource($this->whenLoaded('subject')),
            'created_at'    => $this->created_at?->toDateTimeString(),
            'updated_at'    => $this->updated_at?->toDateTimeString(),
        ];
    }
}
