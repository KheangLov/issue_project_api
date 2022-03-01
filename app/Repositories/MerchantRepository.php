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
}
