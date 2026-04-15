<?php

namespace Pbs\Shop\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'price' => $this->price,
            'description' => $this->description,
            'type' => str_replace('Pbs\\Shop\\Models\\', '', $this->productable_type),
            'details' => $this->resolveDetails(),
        ];
    }

    protected function resolveDetails()
    {
        if (!$this->productable) {
            return null;
        }

        if ($this->productable instanceof \Pbs\Shop\Models\PhysicalProduct) {
            return [
                'weight' => $this->productable->weight,
                'dimensions' => [
                    'w' => $this->productable->width,
                    'h' => $this->productable->height,
                    'l' => $this->productable->length,
                ]
            ];
        }

        if ($this->productable instanceof \Pbs\Shop\Models\DigitalProduct) {
            return [
                'file' => $this->productable->file_url,
                'limit' => $this->productable->download_limit,
            ];
        }

        return $this->productable->toArray();
    }
}
