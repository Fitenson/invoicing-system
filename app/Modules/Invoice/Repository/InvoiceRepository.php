<?php

namespace App\Modules\Invoice\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Common\Repository\BaseRepository;
use App\Modules\Invoice\Model\Invoice;
use App\Modules\Invoice\Model\InvoiceHasProjects;
use App\Modules\Project\Model\Project;


/**
 * Repository layer for the Invoice module.
 *
 * Responsible for interacting with data sources (e.g., Eloquent models or raw DB queries).
 * This layer abstracts data retrieval and persistence logic, keeping it separate from business logic.
 */
class InvoiceRepository extends BaseRepository {
    /**
     *  @param string $class_name       Class name of a model. Example: Invoice::class
     *  @param array $params            Parameter for the backend to perform server-side filtering
     *  @param array $selects           Selects query for the backend to perform in order to display the necessary data.
     *  @param array $extra_filters     Additional condition of filter, if any
     *
     *  @return LengthAwarePaginator $data
    */
    public function getPaginated(string $class_name = Invoice::class, array $params, array $selects, array $extra_filters = [])
    {
        return parent::getPaginated($class_name, $params, $selects, $extra_filters);
    }


    /**
     *  @param string $id       id of the selected Invoice
     *
     *  @return array $invoice      Invoice data along with the projects associated with the selected Invoice
    */
    public function findInvoice(string $id)
    {
        $invoice = Invoice::selectRaw('
            invoices.*,
            users.name as client_name,
            (
                SELECT SUM(projects.total_hours)
                FROM invoice_has_projects
                INNER JOIN projects ON projects.id = invoice_has_projects.project
                WHERE invoice_has_projects.invoice = invoices.id
            ) as total_hours,
            (
                SELECT SUM(projects.rate_per_hour)
                FROM invoice_has_projects
                INNER JOIN projects ON projects.id = invoice_has_projects.project
                WHERE invoice_has_projects.invoice = invoices.id
            ) as total_rate_per_hour,
            (
                SELECT FORMAT(SUM(projects.rate_per_hour * projects.total_hours), 2)
                FROM invoice_has_projects
                INNER JOIN projects ON projects.id = invoice_has_projects.project
                WHERE invoice_has_projects.invoice = invoices.id
            ) as total_income
        ')
        ->leftJoin('users', 'users.id', '=', 'invoices.client')
        ->with([
            'projects.project' => function ($subQuery) {
                $subQuery->select([
                    'id',
                    'name',
                    'rate_per_hour',
                    'total_hours'
                ]);
            }
        ])
        ->where('invoices.id', $id)
        ->firstOrFail()
        ->toArray();

        return $invoice;
    }


    /**
     *  @param string $id       id of the selected Invoice to be deleted
     *
     *  @return bool $results       Return false, if got error, otherwise, return success
    */
    public function destroyInvoice(string $id)
    {
        try {
            DB::beginTransaction();
            $this->destroyProjects($id);
            $results = $this->destroy(Invoice::class, $id);
            DB::commit();
            return $results;
        } catch(\Exception $error) {
            return false;
        }
    }


    /**
     *  @param string $id       id of the selected Project associated with an Invoice record
     *
     *  @return bool $results       Return false, if got error, otherwise, return success
    */
    public function destroyProjects(string $id): bool
    {
        $invoiceHasProjects = InvoiceHasProjects::where('invoice', $id);

        try {
            $invoiceHasProjects->delete();

            return true;
        } catch(\Exception $error) {
            return false;
        }
    }


    /**
     *  Return total income to be displayed on Dashboard
    */
    public function getTotalIncome()
    {
        $invoice = Invoice::selectRaw('
            (
                SELECT FORMAT(SUM(projects.rate_per_hour * projects.total_hours), 2)
                FROM invoice_has_projects
                INNER JOIN projects ON projects.id = invoice_has_projects.project
                WHERE invoice_has_projects.invoice = invoices.id
            ) as total_income
        ')
        ->where('invoices.created_by', Auth::id())
        ->first();

        return $invoice ? $invoice->total_income : '0.00';
    }
}
