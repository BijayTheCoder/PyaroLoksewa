<?php

namespace App\Http\Controllers\Api;

use App\Casts\SyllabusData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SyllabusController extends Controller
{
    protected $syllabusData;

    public function __construct(SyllabusData $syllabusData)
    {
        $this->syllabusData = $syllabusData;
    }

    public function getAll()
    {
        return $this->syllabusData->getAll();
    }

    public function groupByPosition()
    {
        return $this->syllabusData->groupByPosition();
    }
}
