<?php

namespace App\Http\ViewComposers\Admin;
use Illuminate\View\View;
use app\Casts\ServiceData;
use App\Models\Service;

class AdminSyllabusPageViewComposer 
{
    protected $serviceData;

	// public function __construct(ServiceData $serviceData)
    // {
    //  $this->serviceData = $serviceData;
    // }

    public function compose(View $view)
    {
      $view->with('services', Service::where('publish', 1)->get());
    }
}