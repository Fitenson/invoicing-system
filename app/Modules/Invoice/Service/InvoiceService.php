<?php

namespace App\Modules\Invoice\Service;


use App\Common\Service\BaseService;
use App\Modules\Invoice\Model\Invoice;
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
            '*'
        ];

        return $this->invoice_repository->getPaginated(Invoice::class, $params, $selects);
    }


    public function show(string $id)
    {
        $invoice = $this->invoice_repository->show(Invoice::class, $id);
        return $invoice;
    }


    public function create(array $data)
    {
        $data['invoice_number'] = $this->generateAutoNumber(Invoice::class, 'invoice_number', 'INV', '????????');

        $invoice = $this->invoice_repository->create(Invoice::class, $data);

        return $invoice;
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


    public function update(string $id, array $data)
    {
        $invoice = $this->invoice_repository->findById(Invoice::class, $id);
        return $this->invoice_repository->update($invoice, $data);
    }


    public function destroy(string $id)
    {
        return $this->invoice_repository->destroy(Invoice::class, $id);
    }
}
