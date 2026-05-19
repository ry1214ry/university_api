<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::with(['student', 'subject'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'required|integer|min:0|max:100'
        ]);

        $grade = 'F';

        if ($request->marks >= 90) {
            $grade = 'A';
        } elseif ($request->marks >= 80) {
            $grade = 'B';
        } elseif ($request->marks >= 70) {
            $grade = 'C';
        } elseif ($request->marks >= 60) {
            $grade = 'D';
        }

        $result = Result::create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'marks' => $request->marks,
            'grade' => $grade,
            'remarks' => $grade == 'F' ? 'Failed' : 'Passed'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Result Created Successfully',
            'data' => $result
        ], 201);
    }

    public function show(string $id)
    {
        $result = Result::with(['student', 'subject'])->find($id);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Result Not Found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function update(Request $request, string $id)
    {
        $result = Result::find($id);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Result Not Found'
            ], 404);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'required|integer|min:0|max:100'
        ]);

        $grade = 'F';

        if ($request->marks >= 90) {
            $grade = 'A';
        } elseif ($request->marks >= 80) {
            $grade = 'B';
        } elseif ($request->marks >= 70) {
            $grade = 'C';
        } elseif ($request->marks >= 60) {
            $grade = 'D';
        }

        $result->update([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'marks' => $request->marks,
            'grade' => $grade,
            'remarks' => $grade == 'F' ? 'Failed' : 'Passed'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Result Updated Successfully',
            'data' => $result
        ]);
    }

    public function destroy(string $id)
    {
        $result = Result::find($id);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Result Not Found'
            ], 404);
        }

        $result->delete();

        return response()->json([
            'success' => true,
            'message' => 'Result Deleted Successfully'
        ]);
    }
}