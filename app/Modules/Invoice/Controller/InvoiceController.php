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


    /**
     *  Index API
    */
    public function index(Request $request)
    {
        $params = [
            'sort_by' => 'created_at',
            'sort_order' => 'desc'
        ];

        $invoices = $this->invoice_service->getPaginated($params);

        return view('invoice.index', compact('invoices'));
    }

    /**
     *  Create new Invoice API
    */
    public function store(Request $request)
    {
        $post_data = $request->all();

        $this->invoice_service->createInvoice($post_data);

        return redirect()->route('invoices.index')->with('success', 'User deleted successfully');
    }


    /**
     *  Add new project on existing Invoice
     *  @param string $id       Pass the id of the selected Invoice record
     *  @param Request $request     POST request containing the FormData of the selected project to be added
    */
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


    /**
     * @param string $id       Pass the id of the selected Invoice record to be updated
     * @param Request $request     POST request containing the FormData of the fields to be updated
    */
    public function update(string $id, Request $request)
    {
        $post_data = $request->all();
        $update_invoice = $this->invoice_service->updateInvoice($id, $post_data);

        if($update_invoice) {
            return redirect()->route('invoices.show', ['id' => $id])->with('success', 'Invoice updated successfully.');
        }


        return redirect()->back()->with('error', 'Failed to update invoice.');
    }


    /**
     *  @param string $id       Pass the id of the selected Invoice record to be deleted
    */
    public function destroy(string $id)
    {
        $result = $this->invoice_service->destroy(Invoice::class, $id);

        // Check if deletion was successful
        if ($result) {
            // Redirect with success message
            return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully');
        }

        // If deletion failed
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete Invoice'
        ]);
        // return redirect()->back()->with('error', 'Failed to delete invoice');
    }


    /**
     *  @param string $id       Pass id of the selected InvoiceHasProjects record to be deleted
    */
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


    /**
     *  @param string $id       Pass id of the selected Invoice record
    */
    public function generatePDF(string $id)
    {
        $invoice = $this->invoice_service->findInvoice($id);
        $pdf = $this->invoice_service->generateInvoicePDF($invoice);

        return $pdf->inline("invoice_{$invoice['invoice_number']}.pdf");
    }


    /**
     *  @param string $id       Pass id of the selected Invoice record
    */
    public function sendEmail(string $id)
    {
        $send_email = $this->invoice_service->sendEmail($id);

        if($send_email) {
            return response()->json([
                'message' => 'Success'
            ]);
        }

        return response()->json([
            'message' => 'Failed'
        ], 400);
    }
}
