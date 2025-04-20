<?php

namespace App\Modules\Invoice\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use App\Common\Repository\BaseRepository;
use App\Modules\Invoice\Model\Invoice;
use App\Modules\Invoice\Model\InvoiceHasProjects;
use App\Modules\Project\Model\Project;

class InvoiceRepository extends BaseRepository {
    public function getPaginated(string $class_name = Invoice::class, array $params, array $selects, array $extra_filters = [])
    {
        return parent::getPaginated($class_name, $params, $selects, $extra_filters);
    }


    public function findInvoice(string $id)
    {
        $invoice = Invoice::selectRaw('
            invoices.*,
            (
                SELECT SUM(projects.total_hours)
                FROM invoice_has_projects
                INNER JOIN projects ON projects.id = invoice_has_projects.project
                WHERE invoice_has_projects.invoice = invoices.id
            ) as total_hours
        ')
        ->with([
            'projects.project' => function ($query) {
                $query->select([
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
}
