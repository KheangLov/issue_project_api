<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCurriculumVitaeAPIRequest;
use App\Http\Requests\API\UpdateCurriculumVitaeAPIRequest;
use App\Models\CurriculumVitae;
use App\Repositories\CurriculumVitaeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CurriculumVitaeResource;
use Response;

/**
 * Class CurriculumVitaeController
 * @package App\Http\Controllers\API
 */

class CurriculumVitaeAPIController extends AppBaseController
{
    /** @var  CurriculumVitaeRepository */
    private $curriculumVitaeRepository;

    public function __construct(CurriculumVitaeRepository $curriculumVitaeRepo)
    {
        $this->curriculumVitaeRepository = $curriculumVitaeRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/curriculum_vitaes",
     *      summary="Get a listing of the CurriculumVitaes.",
     *      tags={"CurriculumVitae"},
     *      description="Get all CurriculumVitaes",
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
     *                  @SWG\Items(ref="#/definitions/CurriculumVitae")
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
        $curriculumVitaes = $this->curriculumVitaeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(CurriculumVitaeResource::collection($curriculumVitaes), 'Curriculum Vitaes retrieved successfully');
    }

    /**
     * @param CreateCurriculumVitaeAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/curriculum_vitaes",
     *      summary="Store a newly created CurriculumVitae in storage",
     *      tags={"CurriculumVitae"},
     *      description="Store CurriculumVitae",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="CurriculumVitae that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/CurriculumVitae")
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
     *                  ref="#/definitions/CurriculumVitae"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateCurriculumVitaeAPIRequest $request)
    {
        $input = $request->all();

        $curriculumVitae = $this->curriculumVitaeRepository->create($input);

        return $this->sendResponse(new CurriculumVitaeResource($curriculumVitae), 'Curriculum Vitae saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/curriculum_vitaes/{id}",
     *      summary="Display the specified CurriculumVitae",
     *      tags={"CurriculumVitae"},
     *      description="Get CurriculumVitae",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of CurriculumVitae",
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
     *                  ref="#/definitions/CurriculumVitae"
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
        /** @var CurriculumVitae $curriculumVitae */
        $curriculumVitae = $this->curriculumVitaeRepository->find($id);

        if (empty($curriculumVitae)) {
            return $this->sendError('Curriculum Vitae not found');
        }

        return $this->sendResponse(new CurriculumVitaeResource($curriculumVitae), 'Curriculum Vitae retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateCurriculumVitaeAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/curriculum_vitaes/{id}",
     *      summary="Update the specified CurriculumVitae in storage",
     *      tags={"CurriculumVitae"},
     *      description="Update CurriculumVitae",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of CurriculumVitae",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="CurriculumVitae that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/CurriculumVitae")
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
     *                  ref="#/definitions/CurriculumVitae"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateCurriculumVitaeAPIRequest $request)
    {
        $input = $request->all();

        /** @var CurriculumVitae $curriculumVitae */
        $curriculumVitae = $this->curriculumVitaeRepository->find($id);

        if (empty($curriculumVitae)) {
            return $this->sendError('Curriculum Vitae not found');
        }

        $curriculumVitae = $this->curriculumVitaeRepository->update($input, $id);

        return $this->sendResponse(new CurriculumVitaeResource($curriculumVitae), 'CurriculumVitae updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/curriculum_vitaes/{id}",
     *      summary="Remove the specified CurriculumVitae from storage",
     *      tags={"CurriculumVitae"},
     *      description="Delete CurriculumVitae",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of CurriculumVitae",
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
        /** @var CurriculumVitae $curriculumVitae */
        $curriculumVitae = $this->curriculumVitaeRepository->find($id);

        if (empty($curriculumVitae)) {
            return $this->sendError('Curriculum Vitae not found');
        }

        $curriculumVitae->delete();

        return $this->sendSuccess('Curriculum Vitae deleted successfully');
    }
}
