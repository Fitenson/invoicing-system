<?php

namespace App\Modules\Dashboard\Controller;

use App\Http\Controllers\Controller;

/**
 * Controller for handling API requests related to Dashboard.
 *
 * This layer is responsible for receiving HTTP requests,
 * passing input data to the Service layer, and returning appropriate responses.
 * Should remain thin and free of business logic.
 *
 * Note: Currently not being used
 */
class DashboardController extends Controller {
    public function index()
    {
        return view('dashboard.index');
    }
}
