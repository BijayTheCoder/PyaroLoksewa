<?php

namespace App\Http\Controllers\Api;

use App\Casts\CategoryData;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\CategoryCollection;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(CategoryData $categoryData)
    {
        $this->category = $categoryData;
    }
    public function getAll()
    {
        $categories = $this->category->getAll();
        if(isset($categories) && !empty($categories))
        {
            // dd(new CategoryCollection($categories));
            $categorie = new CategoryCollection($categories);
            return response()->json(new CategoryCollection($categories), 200);
        }
    }
}
