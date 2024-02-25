<?php

namespace App\Http\Controllers\Api\V1\Location;

use App\Http\Controllers\Controller;
use App\Models\Location\Country;
use App\Models\Location\State;

class CountryController extends Controller
{
    /**
     * @OA\Get(

     *      path="/api/v1/countries",
     *      operationId="allCountries",
     *      tags={"Location"},
     *      summary="Get all countries",
     *      description="Get all countries",
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *       ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function index()
    {
        return $this->showAll(Country::all());
    }

    /**
     * @OA\Get(
     *      path="/api/v1/countries/{id}",
     *      operationId="showCountryStates",
     *      tags={"Location"},
     *      summary="Show CountryStates",
     *      description="Show CountryStates",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Country ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful signin",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *       ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function show($id)
    {
        return $this->showAll(State::where('country_id', $id)->orderBy('name')->get());
    }
}
