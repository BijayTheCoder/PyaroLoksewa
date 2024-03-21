<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function paginate(){
        return [
            'total'=>$this->total(),
            'count'=>$this->count(),
            'per_page'=>$this->perPage(),
            'current_page'=>$this->currentPage(),
            'total_pages'=>$this->lastPage(),
        ];
    }
}
