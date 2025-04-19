<?php

namespace App\Modules\Invoice\Controller;

use App\Common\Controller\BaseController;
use App\Modules\Invoice\Service\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends BaseController {
    private InvoiceService $invoice_service;


    public function __construct(InvoiceService $invoice_service) {
        $this->invoice_service = $invoice_service;
    }


    public function index(Request $request)
    {
        $params = [
            'sort_by' => 'created_at',
            'sort_order' => 'desc'
        ];

        $invoices = $this->invoice_service->getPaginated($params);

        return view('invoice.index', compact('invoices'));
    }
}
