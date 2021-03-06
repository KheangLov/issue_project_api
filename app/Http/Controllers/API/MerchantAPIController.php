<?php

namespace App\Http\Controllers\API;

use Response;
use App\Models\Merchant;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use App\Http\Resources\MerchantResource;
use App\Repositories\MerchantRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\MerchantAjaxResource;
use App\Http\Requests\API\CreateMerchantAPIRequest;
use App\Http\Requests\API\UpdateMerchantAPIRequest;

/**
 * Class MerchantController
 * @package App\Http\Controllers\API
 */

class MerchantAPIController extends AppBaseController
{
    /** @var  MerchantRepository */
    private $merchantRepository;

    public function __construct(MerchantRepository $merchantRepo)
    {
        $this->merchantRepository = $merchantRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/merchants",
     *      summary="Get a listing of the Merchants.",
     *      tags={"Merchant"},
     *      description="Get all Merchants",
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
     *                  @SWG\Items(ref="#/definitions/Merchant")
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
        $merchants = $this->merchantRepository->getMerchantsAndFilter($request);
        return MerchantResource::collection($merchants);
    }

    public function merchantAjax(Request $request)
    {
        $merchants = $this->merchantRepository->getMerchantsAndFilter($request);
        return MerchantAjaxResource::collection($merchants);
    }

    public function uploadAjax(Request $request)
    {
        $value = $request->file('upload')->getRealPath();
        Cloudder::upload($value, null, [
            "folder" => 'uploads/ckeditor',
            "overwrite" => false,
            "resource_type" => "image",
        ]);

        return response()->json(['url' => Cloudder::show(Cloudder::getPublicId(), ["secure" => "true"])]);
    }

    /**
     * @param CreateMerchantAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/merchants",
     *      summary="Store a newly created Merchant in storage",
     *      tags={"Merchant"},
     *      description="Store Merchant",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Merchant that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Merchant")
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
     *                  ref="#/definitions/Merchant"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateMerchantAPIRequest $request)
    {
        $input = $request->all();

        $merchant = $this->merchantRepository->create($input);

        return $this->sendResponse(new MerchantResource($merchant), 'Merchant saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/merchants/{id}",
     *      summary="Display the specified Merchant",
     *      tags={"Merchant"},
     *      description="Get Merchant",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Merchant",
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
     *                  ref="#/definitions/Merchant"
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
        /** @var Merchant $merchant */
        $merchant = $this->merchantRepository->find($id);

        if (empty($merchant)) {
            return $this->sendError('Merchant not found');
        }

        return $this->sendResponse(new MerchantResource($merchant), 'Merchant retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateMerchantAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/merchants/{id}",
     *      summary="Update the specified Merchant in storage",
     *      tags={"Merchant"},
     *      description="Update Merchant",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Merchant",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Merchant that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Merchant")
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
     *                  ref="#/definitions/Merchant"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateMerchantAPIRequest $request)
    {
        $input = $request->all();

        /** @var Merchant $merchant */
        $merchant = $this->merchantRepository->find($id);

        if (empty($merchant)) {
            return $this->sendError('Merchant not found');
        }

        $merchant = $this->merchantRepository->update($input, $id);

        return $this->sendResponse(new MerchantResource($merchant), 'Merchant updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/merchants/{id}",
     *      summary="Remove the specified Merchant from storage",
     *      tags={"Merchant"},
     *      description="Delete Merchant",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Merchant",
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
    public function destroy($id, Request $request)
    {
        $this->merchantRepository->destoryAndDelete($id, $request);
        return $this->sendSuccess('Merchant deleted successfully');
    }

    public function restore($id)
    {
        $this->merchantRepository->restoreData($id);
        return $this->sendSuccess('Merchant retored successfully');
    }
}
