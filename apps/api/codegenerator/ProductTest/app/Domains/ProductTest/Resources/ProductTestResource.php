<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductTestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price
        ];
    }
}