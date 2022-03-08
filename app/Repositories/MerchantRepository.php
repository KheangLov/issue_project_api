<?php

namespace App\Repositories;

use App\Models\Merchant;
use App\Repositories\BaseRepository;

/**
 * Class MerchantRepository
 * @package App\Repositories
 * @version March 1, 2022, 6:12 pm +07
*/

class MerchantRepository extends BaseRepository
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
        return Merchant::class;
    }

    public function getMerchantsAndFilter($request)
    {
        $perPage = $request->per_page ?? 10;
        $search = $request->search ?? '';
        $trashed = filter_var($request->trashed, FILTER_VALIDATE_BOOLEAN);

        $query = $this->model;
        if ($trashed) {
            $query = $query->onlyTrashed();
        }

        if ($search) {
            $query = $query->where('name', 'ILIKE', '%' . $search . '%');
        }

        return $query
            ->orderBy('updated_at', 'DESC')
            ->paginate($perPage);
    }
}
