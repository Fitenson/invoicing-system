<?php

namespace App\Common\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


abstract class BaseRepository {
    /**
     *  @param string $class_name   Class name of a model. Example: User::class
     *  @param array $data  POST data for the system to save
     *
     *  @return Model $model
    */
    public function create(string $class_name, array $data)
    {
        $model = $class_name::create($data);
        return $model;
    }


    /**
     *  @param string $class_name   Class name of a model. Example: User::class
     *  @param array $params    For server side filters and pagination
     *  @param array $selects   Selected columns query to display on table gridview
     *  @param array $extra_filters     Extra conditions to display data
     *
     *  @return LengthAwarePaginator $paginated
    */
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


    /**
     *  @param string $class_name   Class name of a model. Example: User::class
     *  @param string $id       ID of the selected record
     *  @param array $selects   Selected columns that wants to be displays
     *
     *  @return array $model    Return a record in an associative array
    */
    public function show(string $class_name, string $id, array $selects = ['*'])
    {
        $model = $class_name::select($selects)->where('id', $id)->first();

        if (empty($model)) {
            abort(404, 'Record Not Found');
        }

        return $model->toArray();
    }
}
