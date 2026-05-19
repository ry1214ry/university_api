<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display all courses
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $courses = Course::with('department')
            ->when($search, function ($query) use ($search) {
                $query->where('course_name', 'LIKE', "%{$search}%")
                      ->orWhere('course_code', 'LIKE', "%{$search}%")
                      ->orWhere('semester', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Course List',
            'data' => $courses
        ], 200);
    }

    /**
     * Store new course
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:100|unique:courses',
            'credit' => 'required|integer',
            'semester' => 'required|string|max:50',
            'department_id' => 'required|exists:departments,id'
        ]);

        $course = Course::create([
            'course_name' => $request->course_name,
            'course_code' => $request->course_code,
            'credit' => $request->credit,
            'semester' => $request->semester,
            'department_id' => $request->department_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Course Created Successfully',
            'data' => $course
        ], 201);
    }

    /**
     * Show single course
     */
    public function show(string $id)
    {
        $course = Course::with([
            'department'
        ])->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course Not Found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course Detail',
            'data' => $course
        ], 200);
    }

    /**
     * Update course
     */
    public function update(Request $request, string $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course Not Found'
            ], 404);
        }

        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:100|unique:courses,course_code,' . $id,
            'credit' => 'required|integer',
            'semester' => 'required|string|max:50',
            'department_id' => 'required|exists:departments,id'
        ]);

        $course->update([
            'course_name' => $request->course_name,
            'course_code' => $request->course_code,
            'credit' => $request->credit,
            'semester' => $request->semester,
            'department_id' => $request->department_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Course Updated Successfully',
            'data' => $course
        ], 200);
    }

    /**
     * Delete course
     */
    public function destroy(string $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course Not Found'
            ], 404);
        }

        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course Deleted Successfully'
        ], 200);
    }
}