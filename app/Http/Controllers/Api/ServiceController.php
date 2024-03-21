<?php

namespace App\Http\Controllers\Api;

use App\Casts\ServiceData;
use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\ServiceCollection;
use Exception;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $service;

    public function __construct(ServiceData $serviceData)
    {
        $this->service = $serviceData;
    }
    public function getPaginated(Request $request)
    {
        $pageSize = $request->get('page_size');
        $services = $this->service->getPaginated($pageSize);
        if(isset($services) && !empty($services))
        {
            // dd(new CategoryCollection($categories));
            return response()->json(new ServiceCollection($services), 200);
        }
    }
    public function getAll(Request $request)
    {
        $services = $this->service->getAll();
        if(isset($services) && !empty($services))
        {
            // dd(new CategoryCollection($categories));
            return response()->json(new ServiceCollection($services), 200);
        }
    }
}
