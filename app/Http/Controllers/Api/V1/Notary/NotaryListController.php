<?php

namespace App\Http\Controllers\Api\V1\Notary;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notary\NotaryResource;
use App\Models\NotaryList;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class NotaryListController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/notary/notary-list?per_page={per_page}",
     *      operationId="notaryList",
     *      tags={"Notary"},
     *      summary="Get notary list",
     *      description="get notary list",
     *
     *      @OA\Parameter(
     *          name="date",
     *          description="Date",
     *          required=false,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *      @OA\Parameter(
     *          name="time",
     *          description="Time",
     *          required=false,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *      @OA\Parameter(
     *          name="per_page",
     *          description="per_page",
     *          required=false,
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
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function index()
    {
        $date_query = request()->get('date') ? request()->get('date') : null;
        $time_query = request()->get('time') ? request()->get('time') : null;
        $country_id = request()->get('country_id') ? request()->get('country_id') : null;
        $state_id = request()->get('state_id') ? request()->get('state_id') : null;
        $role = request()->get('role') ? request()->get('role') : null;

        // $notaries = NotarySchedule::query()
        //         ->selectRaw('users.*')
        //         ->selectRaw('count(notary_schedules.time) as user_time_schedule_count')
        //         ->selectRaw('count(notary_schedules.date) as user_date_schedule_count')
        //         ->selectRaw('notary_schedules.date as date')
        //         ->selectRaw('notary_schedules.time as time')
        //         ->join('users', 'users.id', '=', 'notary_schedules.notary_id')
        //         ->when($date_query, function (Builder $builder, $date_query) {
        //             $builder->where('notary_schedules.date', '=', $date_query);
        //         })
        //         ->when($time_query, function (Builder $builder, $time_query) {
        //             $builder->where('notary_schedules.time', '=', $time_query);
        //         })
        //         ->when($country_id, function (Builder $builder, $country_id) {
        //             $builder->where('users.country_id', '=', $country_id);
        //         })
        //         ->when($state_id, function (Builder $builder, $state_id) {
        //             $builder->where('users.state_id', '=', $state_id);
        //         })
        //         ->groupBy('users.id')
        //         ->latest()
        //         ->get();

        // $notaries = User::query()
        //         ->selectRaw('users.*')
        //         // ->selectRaw('COUNT(users.id) as total_users')
        //         // ->selectRaw('count(notary_schedules.time) as user_time_schedule_count')
        //         // ->selectRaw('count(notary_schedules.date) as user_date_schedule_count')
        //         // ->selectRaw('notary_schedules.date as date')
        //         // ->selectRaw('notary_schedules.time as time')
        //         ->leftJoin('notary_schedules', 'notary_schedules.notary_id', '=', 'users.id')
        //         ->when($date_query, function (Builder $builder, $date_query) {
        //             $builder->where('date', '=', $date_query);
        //         })
        //         ->when($time_query, function (Builder $builder, $time_query) {
        //             $builder->where('time', '=', $time_query);
        //         })
        //         ->when($country_id, function (Builder $builder, $country_id) {
        //             $builder->where('users.country_id', '=', $country_id);
        //         })
        //         ->when($state_id, function (Builder $builder, $state_id) {
        //             $builder->where('users.state_id', '=', $state_id);
        //         })
        //         ->groupBy('users.id')
        //         ->latest()
        //         ->get();

        $notaries = NotaryList::query()
            ->with('notaryCalendar')->selectRaw('users.*')
                // ->selectRaw('COUNT(users.id) as total_users')
                // ->selectRaw('count(notary_schedules.time) as user_time_schedule_count')
                // ->selectRaw('count(notary_schedules.date) as user_date_schedule_count')
                // ->selectRaw('notary_schedules.date as date')
                // ->selectRaw('notary_schedules.time as time')
            ->leftJoin('notary_schedules', 'notary_schedules.notary_id', '=', 'users.id')
            ->where('users.role', '=', $role)
            ->when($date_query, function (Builder $builder, $date_query) {
                $builder->where('date', '=', $date_query);
            })
            ->when($time_query, function (Builder $builder, $time_query) {
                $builder->where('start_time', '=', $time_query);
            })
            ->when($country_id, function (Builder $builder, $country_id) {
                $builder->where('users.country_id', '=', $country_id);
            })
            ->when($state_id, function (Builder $builder, $state_id) {
                $builder->where('users.state_id', '=', $state_id);
            })
            ->groupBy('users.id')
            ->latest()
            ->get();

        return $this->showAll($notaries);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/notary/notary-list/{id}",
     *      operationId="showNotaryProfile",
     *      tags={"Notary"},
     *      summary="Show NotaryProfile",
     *      description="Show NotaryProfile",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="NotaryProfile ID",
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
        return new NotaryResource(User::with('feedbacks')->find($id));
    }
}
