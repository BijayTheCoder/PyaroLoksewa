<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\Common\PaginationCollection;
use App\Http\Resources\ServiceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceCollection extends PaginationCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'=>ServiceResource::collection($this->collection),
            // 'pagination'=>$this->paginate()
        ];    
    }
}
