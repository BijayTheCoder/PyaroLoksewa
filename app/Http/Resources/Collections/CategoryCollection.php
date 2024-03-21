<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Common\PaginationCollection;

class CategoryCollection extends PaginationCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data'=>CategoryResource::collection($this->collection),
            // 'pagination'=>$this->paginate()
        ];
    }
}
