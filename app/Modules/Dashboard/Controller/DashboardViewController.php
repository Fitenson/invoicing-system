<?php

namespace App\Modules\Dashboard\Controller;

use App\Http\Controllers\Controller;
use App\Modules\Dashboard\Service\DashboardService;


/**
 * ViewController for rendering and displaying data on the Dashboard.
 *
 * This controller is responsible for:
 * - Rendering Blade views
 * - Coordinating with various Services from other modules to retrieve and display data
 *
 * Responsibilities:
 * - Should remain thin and focused only on view-related logic
 * - Must delegate business logic to Service layers
 */
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
