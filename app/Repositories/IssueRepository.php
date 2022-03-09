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

    public function getIssuesAndFilter($request)
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

    public function countData()
    {
        $model = $this->model;

        return [
            [
                'text' => 'All Issues',
                'count' => $model->count(),
            ],
            [
                'text' => '<b>ISSUE TYPE:</b> Bug',
                'count' => $model->where('issue_type', 'ILIKE', 'bug')->count(),
            ],
            [
                'text' => '<b>ISSUE TYPE:</b> Improvement',
                'count' => $model->where('issue_type', 'ILIKE', 'improvement')->count(),
            ],
            [
                'text' => '<b>Status:</b> Issued',
                'count' => $model->where('status', 'ILIKE', 'issued')->count(),
            ],
            [
                'text' => '<b>Status:</b> Resolved',
                'count' => $model->where('status', 'ILIKE', 'resolved')->count(),
            ],
            [
                'text' => '<b>API Type:</b> Sandbox',
                'count' => $model->where('api_type', 'ILIKE', 'sandbox')->count(),
            ],
            [
                'text' => '<b>API Type:</b> Production',
                'count' => $model->where('api_type', 'ILIKE', 'production')->count(),
            ],
        ];
    }
}
