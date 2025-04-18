<?php

namespace App\Common\Repository;


abstract class BaseRepository {
    public function create(string $class_name, array $data)
    {
        return $class_name::create($data);
    }


    public function getPaginated(string $class_name, array $params, array $selects, array $extra_filters = [])
    {
        $query = $class_name::select($selects);

        // Search
        if (!empty($params['search']) && !empty($params['search_columns'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($params, $search) {
                foreach ($params['search_columns'] as $column) {
                    $q->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
        }

        // Filters
        foreach ($extra_filters as $column => $value) {
            if (!is_null($value)) {
                $query->where($column, $value);
            }
        }

        // Sorting
        $sortBy = $params['sort_by'] ?? 'id';
        $sortOrder = $params['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Pagination (Laravel handles current page automatically)
        $perPage = $params['per_page'] ?? 10;
        $paginated = $query->paginate($perPage);

        return $paginated;

        // return [
        //     'total' => $paginated->total(),
        //     'rows' => $paginated->items(),
        //     // 'current_page' => $paginated->currentPage(),
        //     // 'last_page' => $paginated->lastPage(),
        //     // 'per_page' => $paginated->perPage(),
        // ];
    }
}
