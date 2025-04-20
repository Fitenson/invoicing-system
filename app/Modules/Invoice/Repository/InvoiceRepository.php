<?php

namespace App\Modules\Invoice\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use App\Common\Repository\BaseRepository;
use App\Modules\Invoice\Model\Invoice;
use App\Modules\Project\Model\Project;

class InvoiceRepository extends BaseRepository {
    public function getPaginated(string $class_name = Invoice::class, array $params, array $selects, array $extra_filters = [])
    {
        return parent::getPaginated($class_name, $params, $selects, $extra_filters);
    }


    public function findInvoice(string $id)
    {
        return Invoice::with([
            'projects.project' => function ($query) {
                $query->select([
                    'id',
                    'name',
                    'rate_per_hour',
                    'total_hours'
                ]);
            }
        ])
        ->where('id', $id)
        ->firstOrFail()
        ->toArray();
    }
}
