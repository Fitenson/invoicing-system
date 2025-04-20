<?php

namespace App\Modules\Dashboard\Controller;

use App\Http\Controllers\Controller;
use App\Modules\Dashboard\Service\DashboardService;

class DashboardViewController extends Controller {
    private DashboardService $dashboard_service;


    public function __construct(DashboardService $dashboard_service) {
        $this->dashboard_service = $dashboard_service;
    }


    public function index()
    {
        $total_records = $this->dashboard_service->getTotalRecords();
        return view('dashboard.index', compact('total_records'));
    }
}
