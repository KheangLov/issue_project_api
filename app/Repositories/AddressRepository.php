<?php

namespace App\Repositories;

use App\Models\Address;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Log;

/**
 * Class AddressRepository
 * @package App\Repositories
 * @version March 27, 2022, 1:54 pm +07
*/

class AddressRepository extends BaseRepository
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
        return Address::class;
    }

    public function addressBoundary($request)
    {
        try {
            $code = $request->code ?? '';
            $subLevel = $request->sub_level ?? true;
            $addresses = $this->model
                ->selectRaw('_code AS admin_code, boundary, center')
                ->whereNotNull('boundary');

            if ($subLevel) {
                if (!$code) {
                    $addresses = $addresses->whereRaw("LENGTH(_code) = 2");
                } else {
                    $addresses = $addresses->where("_code", "LIKE", $code . '__');
                }
            } else {
                if (!$code) {
                    return [];
                } else {
                    $addresses = $addresses->where('_code', $code);
                }
            }

            return $addresses->get();
        } catch (\Throwable $th) {
            Log::error(self::class. '::getAddressWithBoundary() : ' . $th);
            return false;
        }
    }
}
