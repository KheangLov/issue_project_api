<?php

namespace App\Repositories;

use App\Models\Issue;
use App\Repositories\BaseRepository;

/**
 * Class IssueRepository
 * @package App\Repositories
 * @version March 1, 2022, 6:11 pm +07
*/

class IssueRepository extends BaseRepository
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
        return Issue::class;
    }
}
