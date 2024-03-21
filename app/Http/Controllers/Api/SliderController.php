<?php

namespace App\Http\Controllers\Api;

use App\Casts\SliderData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    protected $sliderData;

    public function __construct(SliderData $sliderData)
    {
        $this->sliderData = $sliderData;
    }

    public function getAll()
    {
        return $this->sliderData->getAll();
    }
}
