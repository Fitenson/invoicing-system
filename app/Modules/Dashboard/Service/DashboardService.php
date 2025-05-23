<?php

namespace App\Modules\Dashboard\Service;

use App\Common\Service\BaseService;
use App\Modules\Invoice\Service\InvoiceService;
use App\Modules\Project\Service\ProjectService;
use App\Modules\User\Service\UserService;


/**
 * Service Layer for Dashboard module.
 *
 * This layer is responsible for handling application-level logic,
 * separating complex business operations from the data layer (e.g., repositories or models).
 *
 * Typically used to coordinate saving/updating data, validations, or calling other services,
 * while keeping controllers and models clean from business logic.
 */
class DashboardService extends BaseService {
    private UserService $user_service;
    private ProjectService $project_service;
    private InvoiceService $invoice_service;


    public function __construct(UserService $user_service, ProjectService $project_service, InvoiceService $invoice_service) {
        $this->user_service = $user_service;
        $this->project_service = $project_service;
        $this->invoice_service = $invoice_service;
    }


    /**
     *  Provide the neccessary data to display on Dashboard
    */
    public function getDashboardData()
    {
        $total_client = $this->user_service->getTotalUser();
        $total_project = $this->project_service->getTotalProject();
        $total_invoice = $this->invoice_service->getTotalInvoice();
        $total_income = $this->invoice_service->getTotalIncome();

        return [
            'total_client' => $total_client,
            'total_project' => $total_project,
            'total_invoice' => $total_invoice,
            'total_income' => $total_income
        ];
    }
}
