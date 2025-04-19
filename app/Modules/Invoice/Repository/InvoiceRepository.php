<?php

namespace App\Modules\Invoice\Repository;

use App\Common\Repository\BaseRepository;
use App\Modules\Invoice\Model\Invoice;


class InvoiceRepository extends BaseRepository {
    public function getPaginated(string $class_name = Invoice::class, array $params, array $selects, array $extra_filters = [])
    {
        return parent::getPaginated($class_name, $params, $selects, $extra_filters);
    }
}
