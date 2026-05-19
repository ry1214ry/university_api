<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['student', 'course'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $enrollments
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'semester' => 'required',
            'academic_year' => 'required'
        ]);

        $enrollment = Enrollment::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Enrollment Created Successfully',
            'data' => $enrollment
        ], 201);
    }

    public function show(string $id)
    {
        $enrollment = Enrollment::with(['student', 'course'])->find($id);

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Enrollment Not Found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $enrollment
        ]);
    }

    public function update(Request $request, string $id)
    {
        $enrollment = Enrollment::find($id);

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Enrollment Not Found'
            ], 404);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'semester' => 'required',
            'academic_year' => 'required'
        ]);

        $enrollment->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Enrollment Updated Successfully',
            'data' => $enrollment
        ]);
    }

    public function destroy(string $id)
    {
        $enrollment = Enrollment::find($id);

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Enrollment Not Found'
            ], 404);
        }

        $enrollment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Enrollment Deleted Successfully'
        ]);
    }
}