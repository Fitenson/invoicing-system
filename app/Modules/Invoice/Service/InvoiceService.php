<?php

namespace App\Modules\Invoice\Service;

use Illuminate\Support\Facades\DB;
use Barryvdh\Snappy\Facades\SnappyPdf;

use App\Common\Service\BaseService;
use App\Modules\Invoice\Model\Invoice;
use App\Modules\Invoice\Model\InvoiceHasProjects;
use App\Modules\Invoice\Repository\InvoiceRepository;
use App\Modules\Project\Model\Project;
use App\Modules\Project\Service\ProjectService;
use App\Modules\User\Model\User;
use App\Modules\User\Service\UserService;


class InvoiceService extends BaseService {
    private InvoiceRepository $invoice_repository;
    private UserService $user_service;
    private ProjectService $project_service;


    public function __construct(InvoiceRepository $invoice_repository, UserService $user_service, ProjectService $project_service) {
        $this->invoice_repository = $invoice_repository;
        $this->project_service = $project_service;
        $this->user_service = $user_service;
    }


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


    public function show(string $id)
    {
        $invoice = $this->invoice_repository->show(Invoice::class, $id);
        return $invoice;
    }


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


    public function findInvoice(string $id)
    {
        $invoice = $this->invoice_repository->findInvoice($id);

        return $invoice;
    }


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


    public function destroy(string $class_name, string $id)
    {
        return $this->invoice_repository->destroy($class_name, $id);
    }


    public function getTotalInvoice()
    {
        return $this->invoice_repository->getTotalRecord(Invoice::class);
    }


    public function generateInvoicePDF($invoice)
    {
        $invoice_has_projects = $invoice['projects'];

        return SnappyPdf::loadView('invoice.pdf-template', [
            'invoice' => $invoice,
            'invoice_has_projects' => $invoice_has_projects
        ]);
    }


    public function getTotalIncome()
    {
        return $this->invoice_repository->getTotalIncome();
    }
}
