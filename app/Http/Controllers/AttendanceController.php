<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with(['student', 'subject'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late'
        ]);

        $attendance = Attendance::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Attendance Recorded Successfully',
            'data' => $attendance
        ], 201);
    }

    public function show(string $id)
    {
        $attendance = Attendance::with(['student', 'subject'])->find($id);

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance Not Found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }

    public function update(Request $request, string $id)
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance Not Found'
            ], 404);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late'
        ]);

        $attendance->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Attendance Updated Successfully',
            'data' => $attendance
        ]);
    }

    public function destroy(string $id)
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance Not Found'
            ], 404);
        }

        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendance Deleted Successfully'
        ]);
    }
}