<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $subjects = Subject::with(['course', 'teacher'])
            ->when($search, function ($query) use ($search) {
                $query->where('subject_name', 'LIKE', "%{$search}%")
                    ->orWhere('subject_code', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $subjects
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required',
            'subject_code' => 'required|unique:subjects',
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
            'semester' => 'required'
        ]);

        $subject = Subject::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Subject Created Successfully',
            'data' => $subject
        ], 201);
    }

    public function show(string $id)
    {
        $subject = Subject::with(['course', 'teacher'])->find($id);

        if (!$subject) {
            return response()->json([
                'success' => false,
                'message' => 'Subject Not Found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $subject
        ]);
    }

    public function update(Request $request, string $id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json([
                'success' => false,
                'message' => 'Subject Not Found'
            ], 404);
        }

        $request->validate([
            'subject_name' => 'required',
            'subject_code' => 'required|unique:subjects,subject_code,' . $id,
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
            'semester' => 'required'
        ]);

        $subject->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Subject Updated Successfully',
            'data' => $subject
        ]);
    }

    public function destroy(string $id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json([
                'success' => false,
                'message' => 'Subject Not Found'
            ], 404);
        }

        $subject->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subject Deleted Successfully'
        ]);
    }
}