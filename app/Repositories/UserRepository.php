<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;

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
        $search = $request->search ?? '';
        $trashed = trim($request->trashed) ?? '';

        $query = $this->model;
        if ($trashed) {
            return $trashed;
            $query = $query->onlyTrashed();
        }

        if ($search) {
            $query = $query->orWhere('name', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }
}
