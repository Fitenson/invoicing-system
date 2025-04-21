<?php

namespace App\Modules\Dashboard\Controller;

use App\Http\Controllers\Controller;
use App\Modules\Dashboard\Service\DashboardService;

class DashboardViewController extends Controller {
    private DashboardService $dashboard_service;


    public function __construct(DashboardService $dashboard_service) {
        $this->dashboard_service = $dashboard_service;
    }


    /**
     *  Display the dashboard page and the necessary data
    */
    public function index()
    {
        $data = $this->dashboard_service->getDashboardData();
        return view('dashboard.index', compact('data'));
    }
}
