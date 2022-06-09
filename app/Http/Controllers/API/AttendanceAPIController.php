<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAttendanceAPIRequest;
use App\Http\Requests\API\UpdateAttendanceAPIRequest;
use App\Models\Attendance;
use App\Repositories\AttendanceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\AttendanceResource;
use Response;

/**
 * Class AttendanceController
 * @package App\Http\Controllers\API
 */

class AttendanceAPIController extends AppBaseController
{
    /** @var  AttendanceRepository */
    private $attendanceRepository;

    public function __construct(AttendanceRepository $attendanceRepo)
    {
        $this->attendanceRepository = $attendanceRepo;
    }

    /**
     * Display a listing of the Attendance.
     * GET|HEAD /attendances
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $attendances = $this->attendanceRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(AttendanceResource::collection($attendances), 'Attendances retrieved successfully');
    }

    /**
     * Store a newly created Attendance in storage.
     * POST /attendances
     *
     * @param CreateAttendanceAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAttendanceAPIRequest $request)
    {
        $input = $request->all();

        $attendance = $this->attendanceRepository->create($input);

        return $this->sendResponse(new AttendanceResource($attendance), 'Attendance saved successfully');
    }

    /**
     * Display the specified Attendance.
     * GET|HEAD /attendances/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Attendance $attendance */
        $attendance = $this->attendanceRepository->find($id);

        if (empty($attendance)) {
            return $this->sendError('Attendance not found');
        }

        return $this->sendResponse(new AttendanceResource($attendance), 'Attendance retrieved successfully');
    }

    /**
     * Update the specified Attendance in storage.
     * PUT/PATCH /attendances/{id}
     *
     * @param int $id
     * @param UpdateAttendanceAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAttendanceAPIRequest $request)
    {
        $input = $request->all();

        /** @var Attendance $attendance */
        $attendance = $this->attendanceRepository->find($id);

        if (empty($attendance)) {
            return $this->sendError('Attendance not found');
        }

        $attendance = $this->attendanceRepository->update($input, $id);

        return $this->sendResponse(new AttendanceResource($attendance), 'Attendance updated successfully');
    }

    /**
     * Remove the specified Attendance from storage.
     * DELETE /attendances/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Attendance $attendance */
        $attendance = $this->attendanceRepository->find($id);

        if (empty($attendance)) {
            return $this->sendError('Attendance not found');
        }

        $attendance->delete();

        return $this->sendSuccess('Attendance deleted successfully');
    }
}
