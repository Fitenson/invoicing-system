<?php

namespace App\Modules\Invoice\Controller;


use Illuminate\Http\Request;

use App\Common\Controller\BaseController;
use App\Modules\Invoice\Service\InvoiceService;
use App\Modules\User\Model\User;
use App\Modules\Project\Model\Project;


/**
 * ViewController for rendering and displaying data on the Invoice.
 *
 * This controller is responsible for:
 * - Rendering Blade views
 * - Coordinating with various Services from other modules to retrieve and display data
 *
 * Responsibilities:
 * - Should remain thin and focused only on view-related logic
 * - Must delegate business logic to Service layers
 */
class InvoiceViewController extends BaseController {
    private InvoiceService $invoice_service;


    public function __construct(InvoiceService $invoice_service) {
        $this->invoice_service = $invoice_service;
    }

    /**
     *  Display and render index page
    */
    public function index(Request $request)
    {
        $params = [
            'sort_by' => 'created_at',
            'sort_order' => 'desc',
            'per_page' => 10
        ];

        $invoices = $this->invoice_service->getPaginated($params);
        return view('invoice.index', compact('invoices'));
    }


    /**
     *  Display and render create Invoice page
    */
    public function create()
    {
        $projects = $this->invoice_service->findAll(Project::class);
        $users = $this->invoice_service->findAll(User::class);

        return view('invoice.create', compact('projects', 'users'));
    }


    /**
     *  Display and render show Invoice page
    */
    public function show(string $id)
    {
        //  Invoice data
        $invoice = $this->invoice_service->findInvoice($id);
        $invoice_has_projects = $invoice['projects'];

        //  For dropdowns
        $projects = $this->invoice_service->findAll(Project::class);
        $users = $this->invoice_service->findAll(User::class);

        return view('invoice.show', compact('invoice', 'invoice_has_projects', 'projects', 'users'));
    }
}
