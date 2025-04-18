<?php

namespace App\Modules\Dashboard\Controller;

use App\Http\Controllers\Controller;


class DashboardController extends Controller {
    public function index()
    {
        return view('dashboard.index');
    }
}
