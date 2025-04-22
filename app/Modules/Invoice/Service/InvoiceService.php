<?php

namespace App\Modules\Invoice\Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\Snappy\Facades\SnappyPdf;

use App\Common\Service\BaseService;
use App\Modules\Invoice\Mail\InvoiceMail;
use App\Modules\Invoice\Model\Invoice;
use App\Modules\Invoice\Model\InvoiceHasProjects;
use App\Modules\Invoice\Repository\InvoiceRepository;
use App\Modules\Project\Model\Project;
use App\Modules\Project\Service\ProjectService;
use App\Modules\User\Model\User;
use App\Modules\User\Service\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


/**
 * Service Layer for Invoice module.
 *
 * This layer is responsible for handling application-level logic,
 * separating complex business operations from the data layer (e.g., repositories or models).
 *
 * Typically used to coordinate saving/updating data, validations, or calling other services,
 * while keeping controllers and models clean from business logic.
 */
class InvoiceService extends BaseService {
    private InvoiceRepository $invoice_repository;
    private UserService $user_service;
    private ProjectService $project_service;


    public function __construct(InvoiceRepository $invoice_repository, UserService $user_service, ProjectService $project_service) {
        $this->invoice_repository = $invoice_repository;
        $this->project_service = $project_service;
        $this->user_service = $user_service;
    }


    /**
     *  Use to implement server side pagination, filtering and sorting
     *  @param array $params        Parameter that determine which page to render
    */
    public function getPaginated(array $params)
    {
        $selects = [
            '*',
            'client_name' => User::select(['name'])->whereColumn('users.id', 'invoices.client'),
            'created_by_name' => User::select(['name'])->whereColumn('users.id', 'invoices.created_by'),
            'updated_by_name' => User::select(['name'])->whereColumn('users.id', 'invoices.updated_by'),

            'total_projects' => InvoiceHasProjects::select(DB::raw('COUNT(*)'))
            ->whereColumn('invoice_has_projects.invoice', 'invoices.id'),

            'total_rate_per_hour' => InvoiceHasProjects::join('projects', 'projects.id', '=', 'invoice_has_projects.project')
            ->select(DB::raw('SUM(projects.rate_per_hour)'))
            ->whereColumn('invoice_has_projects.invoice', 'invoices.id'),

            'total_hours' => InvoiceHasProjects::join('projects', 'projects.id', '=', 'invoice_has_projects.project')
            ->select(DB::raw('SUM(projects.total_hours)'))
            ->whereColumn('invoice_has_projects.invoice', 'invoices.id'),

            'total_income' => InvoiceHasProjects::join('projects', 'projects.id', '=', 'invoice_has_projects.project')
            ->select(DB::raw('FORMAT(SUM(projects.total_hours * projects.rate_per_hour), 2)'))
            ->whereColumn('invoice_has_projects.invoice', 'invoices.id'),
        ];

        return $this->invoice_repository->getPaginated(Invoice::class, $params, $selects);
    }


    /**
     *  Return Invoice data
     *  @param string $id       Pass id of the selected Invoice
    */
    public function show(string $id)
    {
        $invoice = $this->invoice_repository->show(Invoice::class, $id);
        return $invoice;
    }

    /**
     *  Create new data based on provided $class_name
     *  @param string $class_name       Pass class name of a model within Invoice module
     *  @param array $data              Pass data to be saved in database
     *
     *  @return Model $model            Return model of the saved data
    */
    public function create(string $class_name, array $data)
    {
        $model = null;

        switch ($class_name) {
            case Invoice::class:
                $data['invoice_number'] = $this->generateAutoNumber(Invoice::class, 'INV', '????????', 'invoice_number');
                $model = $this->invoice_repository->create(Invoice::class, $data);
                break;

            case InvoiceHasProjects::class:
                $model = $this->invoice_repository->create(InvoiceHasProjects::class, $data);
                break;

            default:
                throw new \Exception("Unsupported class name: {$class_name}");
        }

        return $model;
    }


    /**
     *  Create an invoice and link associated projects to the saved invoice record
     *  @param array $data
    */
    public function createInvoice(array $data)
    {
        $invoice_data = $data['invoice'];
        $invoice_has_projects = !empty($data['invoice_has_projects']) ? $data['invoice_has_projects'] : [];


        try {
            DB::beginTransaction();

            $invoice = $this->create(Invoice::class, $invoice_data);
            $invoiceId = $invoice->id;

            if(!empty($invoice_has_projects)) {
                foreach($invoice_has_projects as &$project) {
                    $project['invoice'] = $invoiceId;
                    $results[] = $this->create(InvoiceHasProjects::class, $project);
                }
            }

            DB::commit();

            return $invoice;
        } catch(\Exception $error) {
            DB::rollBack();
            throw $error;
        }
    }


    /**
     *  Used for display clients dropdown and project dropdown
    */
    public function findAll(string $class_name)
    {
        $data = [];

        switch($class_name) {
            case User::class:
                $data = $this->user_service->findAll();
                break;

            case Project::class:
                $data = $this->project_service->findAll();
                break;
            default:
                $data = $this->invoice_repository->findAll(Invoice::class);
                break;
        }

        return $data;
    }


    /**
     *  Return Invoice along with the Projects data associated with the selected Invoice
    */
    public function findInvoice(string $id)
    {
        $invoice = $this->invoice_repository->findInvoice($id);

        return $invoice;
    }


    /**
     *  Update a record based on class name of a model within Invoice module
     *  @param string $id               Pass id of the selected record
     *  @param array $data              Pass data to be saved on the database
     *  @param string $class_name       Pass class name of a model within Invoice module
    */
    public function update(string $id, array $data, string $class_name)
    {
        $model = null;

        switch($class_name) {
            case InvoiceHasProjects::class:
                $model = $this->invoice_repository->findById(InvoiceHasProjects::class, $id);
                break;

            default:
                $model = $this->invoice_repository->findById(Invoice::class, $id);
        }

        return $this->invoice_repository->update($model, $data);
    }


    /**
     *  Update an Invoice record
     *  @param string $id               Pass id of the selected record
     *  @param array $data              Pass data to be saved on the database
    */
    public function updateInvoice(string $id, array $data)
    {
        $invoice_data = $data['invoice'];
        $invoice_has_projects = !empty($data['invoice_has_projects']) ? $data['invoice_has_projects'] : [];

        $invoice = $this->invoice_repository->findById(Invoice::class, $id);

        try {
            DB::beginTransaction();

            $this->invoice_repository->update($invoice, $invoice_data);

            if(!empty($invoice_has_projects)) {
                foreach($invoice_has_projects as $project) {
                    $project['invoice'] = $id;
                    $this->invoice_repository->updateOrCreate(InvoiceHasProjects::class, $project);
                }
            }

            DB::commit();

            return $invoice;
        } catch(\Exception $error) {
            DB::rollBack();
            return false;
        }
    }

    /**
     *  Delete a record based on provided class name
     *
     *  @param string $class_name       Pass class name of a model within Invoice module
     *  @param string $id               Pass the id of the selected record to be deleted
    */
    public function destroy(string $class_name, string $id)
    {
        return $this->invoice_repository->destroy($class_name, $id);
    }


    /**
     *  Return the total number of invoices in the system
    */
    public function getTotalInvoice()
    {
        return $this->invoice_repository->getTotalRecord(Invoice::class);
    }

    /**
     *  Return the total income of invoices in the system
    */
    public function getTotalIncome()
    {
        return $this->invoice_repository->getTotalIncome();
    }

    /**
     *  Generate Invoice PDF
     *  @param array $invoice       Pass Invoice data in array format
    */
    public function generateInvoicePDF($invoice)
    {
        $invoice_has_projects = $invoice['projects'];

        $pdf = SnappyPdf::loadView('invoice.pdf-template', [
            'invoice' => $invoice,
            'invoice_has_projects' => $invoice_has_projects
        ]);

        return $pdf;
    }


    /**
     *  Send Email with Invoice PDF attached to the email
     *  @param string $Id       Pass id of the selected Invoice
    */
    public function sendEmail(string $id)
    {
        try {
            $invoice = $this->findInvoice($id);
            $pdf = $this->generateInvoicePDF($invoice);

            // Prepare mail data
            $mail_data = [
                'client_name' => $invoice['client_name'],
                'invoice_number' => $invoice['invoice_number'],
                'total_income' => $invoice['total_income'],
            ];

            $auth_user = Auth::user();
            $user_email = $auth_user->email;
            $file_name = "invoice_{$invoice['invoice_number']}.pdf";

            Mail::to($user_email)->send(
                (new InvoiceMail($mail_data))
                ->attachData($pdf->output(), $file_name, [
                    'mime' => 'application/pdf',
                ])
            );

            return true;
        } catch(\Exception $error) {
            return false;
        }
    }
}
