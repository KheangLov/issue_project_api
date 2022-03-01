<?php

namespace App\Repositories;

use App\Models\CurriculumVitae;
use App\Repositories\BaseRepository;

/**
 * Class CurriculumVitaeRepository
 * @package App\Repositories
 * @version January 22, 2022, 3:08 pm UTC
*/

class CurriculumVitaeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'first_name',
        'last_name',
        'gender,10',
        'phone',
        'email'
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
        return CurriculumVitae::class;
    }
}
