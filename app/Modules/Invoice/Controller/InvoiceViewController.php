<?php

namespace App\Modules\Invoice\Controller;


use Illuminate\Http\Request;

use App\Common\Controller\BaseController;
use App\Modules\Invoice\Service\InvoiceService;
use App\Modules\User\Model\User;
use App\Modules\Project\Model\Project;


class InvoiceViewController extends BaseController {
    private InvoiceService $invoice_service;


    public function __construct(InvoiceService $invoice_service) {
        $this->invoice_service = $invoice_service;
    }


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


    public function create()
    {
        $projects = $this->invoice_service->findAll(Project::class);
        $users = $this->invoice_service->findAll(User::class);

        return view('invoice.create', compact('projects', 'users'));
    }


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
