<?php

namespace App\Modules\Invoice\Controller;

use App\Modules\Invoice\Service\InvoiceService;
use Illuminate\Http\Request;
use App\Common\Controller\BaseController;
use App\Modules\Invoice\Model\Invoice;
use App\Modules\Invoice\Model\InvoiceHasProjects;



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


    public function store(Request $request)
    {
        $post_data = $request->all();

        $this->invoice_service->createInvoice($post_data);

        return redirect()->route('invoices.index')->with('success', 'User deleted successfully');
    }


    public function storeProject(string $id, Request $request)
    {
        $post_data = $request->all();

        $invoice_has_projects = $post_data['invoice_has_projects'];
        $invoice_has_projects['invoice'] = $id;

        $this->invoice_service->create(InvoiceHasProjects::class, $invoice_has_projects);

        return response()->json([
            'success' => true,
            'message' => 'Project added successfully!',
        ], 201);

        // return redirect()->route('invoices.show', ['id' => $id])->with('success', 'Invoice updated successfully.');
    }


    public function update(string $id, Request $request)
    {
        $post_data = $request->all();
        $update_invoice = $this->invoice_service->updateInvoice($id, $post_data);

        if($update_invoice) {
            return redirect()->route('invoices.show', ['id' => $id])->with('success', 'Invoice updated successfully.');
        }


        return redirect()->back()->with('error', 'Failed to update invoice.');
    }


    public function destroy(string $id)
    {
        $result = $this->invoice_service->destroy($id);

        // Check if deletion was successful
        if ($result) {
            // Redirect with success message
            return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully');
        }

        // If deletion failed
        // die('Hihi');
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete Invoice'
        ]);
        // return redirect()->back()->with('error', 'Failed to delete invoice');
    }


    public function destroyProjects(string $id)
    {
        $result = $this->invoice_service->destroy(InvoiceHasProjects::class, $id);

        // Check if deletion was successful
        if ($result) {
            // Redirect with success message
            return response()->json([
                'success' => true,
                'message' => 'Project remove successfully!',
            ]);

            // return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully');
        }

        // If deletion failed
        return redirect()->back()->with('error', 'Failed to delete invoice');
    }
}
