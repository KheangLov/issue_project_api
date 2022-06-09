<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version January 23, 2022, 3:42 am UTC
*/

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    public function register($request)
    {
        return $this->model->create($request->only(['name', 'email', 'password']));
    }

    public function getUsersAndFilter($request)
    {
        $perPage = $request->per_page ?? 10;
        $sort = $request->sort ?? 'updated_at';
        $order = $request->order ?? 'DESC';
        $search = $request->search ?? '';
        $trashed = filter_var($request->trashed, FILTER_VALIDATE_BOOLEAN);

        $query = $this->model->where('id', '!=', 1);
        if ($trashed) {
            $query = $query->onlyTrashed();
        }

        if ($search) {
            $query = $query->where(function ($q) use ($search) {
                $q->orWhere('name', 'ILIKE', '%' . $search . '%')
                    ->orWhere('email', 'ILIKE', '%' . $search . '%');
            });
        }

        return $query
            ->orderBy($sort, $order)
            ->paginate($perPage);
    }

}
