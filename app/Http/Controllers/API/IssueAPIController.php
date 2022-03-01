<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateIssueAPIRequest;
use App\Http\Requests\API\UpdateIssueAPIRequest;
use App\Models\Issue;
use App\Repositories\IssueRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\IssueResource;
use Response;

/**
 * Class IssueController
 * @package App\Http\Controllers\API
 */

class IssueAPIController extends AppBaseController
{
    /** @var  IssueRepository */
    private $issueRepository;

    public function __construct(IssueRepository $issueRepo)
    {
        $this->issueRepository = $issueRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/issues",
     *      summary="Get a listing of the Issues.",
     *      tags={"Issue"},
     *      description="Get all Issues",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Issue")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $issues = $this->issueRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(IssueResource::collection($issues), 'Issues retrieved successfully');
    }

    /**
     * @param CreateIssueAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/issues",
     *      summary="Store a newly created Issue in storage",
     *      tags={"Issue"},
     *      description="Store Issue",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Issue that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Issue")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Issue"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateIssueAPIRequest $request)
    {
        $input = $request->all();

        $issue = $this->issueRepository->create($input);

        return $this->sendResponse(new IssueResource($issue), 'Issue saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/issues/{id}",
     *      summary="Display the specified Issue",
     *      tags={"Issue"},
     *      description="Get Issue",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Issue",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Issue"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Issue $issue */
        $issue = $this->issueRepository->find($id);

        if (empty($issue)) {
            return $this->sendError('Issue not found');
        }

        return $this->sendResponse(new IssueResource($issue), 'Issue retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateIssueAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/issues/{id}",
     *      summary="Update the specified Issue in storage",
     *      tags={"Issue"},
     *      description="Update Issue",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Issue",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Issue that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Issue")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Issue"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateIssueAPIRequest $request)
    {
        $input = $request->all();

        /** @var Issue $issue */
        $issue = $this->issueRepository->find($id);

        if (empty($issue)) {
            return $this->sendError('Issue not found');
        }

        $issue = $this->issueRepository->update($input, $id);

        return $this->sendResponse(new IssueResource($issue), 'Issue updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/issues/{id}",
     *      summary="Remove the specified Issue from storage",
     *      tags={"Issue"},
     *      description="Delete Issue",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Issue",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Issue $issue */
        $issue = $this->issueRepository->find($id);

        if (empty($issue)) {
            return $this->sendError('Issue not found');
        }

        $issue->delete();

        return $this->sendSuccess('Issue deleted successfully');
    }
}
