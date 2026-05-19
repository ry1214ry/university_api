<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    
    public function index(Request $request)
    {
        $search = $request->search;

        $students = Student::with(['department', 'course'])
            ->when($search, function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('student_id', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Student List',
            'data' => $students
        ], 200);
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|unique:students',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:students',
            'phone' => 'required',
            'address' => 'required',
            'department_id' => 'required|exists:departments,id',
            'course_id' => 'required|exists:courses,id',
            'year_level' => 'required',
            'status' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('students', 'public');
        }

        $student = Student::create([
            'student_id' => $request->student_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'photo' => $photoPath,
            'department_id' => $request->department_id,
            'course_id' => $request->course_id,
            'year_level' => $request->year_level,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student Created Successfully',
            'data' => $student
        ], 201);
    }

    /**
     * Display the specified student
     */
    public function show(string $id)
    {
        $student = Student::with(['department', 'course'])->find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student Not Found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Student Detail',
            'data' => $student
        ], 200);
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, string $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student Not Found'
            ], 404);
        }

        $request->validate([
            'student_id' => 'required|unique:students,student_id,' . $id,
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|unique:students,email,' . $id,
            'phone' => 'required',
            'address' => 'required',
            'department_id' => 'required|exists:departments,id',
            'course_id' => 'required|exists:courses,id',
            'year_level' => 'required',
            'status' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('photo')) {

            if ($student->photo && file_exists(storage_path('app/public/' . $student->photo))) {
                unlink(storage_path('app/public/' . $student->photo));
            }

            $student->photo = $request->file('photo')->store('students', 'public');
        }

        $student->update([
            'student_id' => $request->student_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'photo' => $student->photo,
            'department_id' => $request->department_id,
            'course_id' => $request->course_id,
            'year_level' => $request->year_level,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student Updated Successfully',
            'data' => $student
        ], 200);
    }

    /**
     * Remove the specified student
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student Not Found'
            ], 404);
        }

        if ($student->photo && file_exists(storage_path('app/public/' . $student->photo))) {
            unlink(storage_path('app/public/' . $student->photo));
        }

        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student Deleted Successfully'
        ], 200);
    }
}