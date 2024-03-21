<?php

namespace App\Http\Controllers\Api;

use App\Casts\NoticeData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    protected $noticeData;

    public function __construct(NoticeData $noticeData)
    {
        $this->noticeData = $noticeData;
    }

    public function getAll()
    {
        return $this->noticeData->getAll();
    }
}
