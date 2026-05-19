<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $teachers = Teacher::with('department')
            ->when($search, function ($query) use ($search) {
                $query->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('teacher_id', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $teachers
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|unique:teachers',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'email' => 'required|email|unique:teachers',
            'phone' => 'required',
            'specialization' => 'required',
            'salary' => 'required',
            'address' => 'required',
            'department_id' => 'required|exists:departments,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('teachers', 'public');
        }

        $teacher = Teacher::create([
            'teacher_id' => $request->teacher_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'salary' => $request->salary,
            'address' => $request->address,
            'department_id' => $request->department_id,
            'photo' => $photoPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Teacher Created Successfully',
            'data' => $teacher
        ], 201);
    }

    public function show(string $id)
    {
        $teacher = Teacher::with('department')->find($id);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher Not Found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $teacher
        ]);
    }

    public function update(Request $request, string $id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher Not Found'
            ], 404);
        }

        $request->validate([
            'teacher_id' => 'required|unique:teachers,teacher_id,' . $id,
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'email' => 'required|email|unique:teachers,email,' . $id,
            'phone' => 'required',
            'specialization' => 'required',
            'salary' => 'required',
            'address' => 'required',
            'department_id' => 'required|exists:departments,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('photo')) {

            if ($teacher->photo && file_exists(storage_path('app/public/' . $teacher->photo))) {
                unlink(storage_path('app/public/' . $teacher->photo));
            }

            $teacher->photo = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update([
            'teacher_id' => $request->teacher_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'salary' => $request->salary,
            'address' => $request->address,
            'department_id' => $request->department_id,
            'photo' => $teacher->photo,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Teacher Updated Successfully',
            'data' => $teacher
        ]);
    }

    public function destroy(string $id)
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher Not Found'
            ], 404);
        }

        if ($teacher->photo && file_exists(storage_path('app/public/' . $teacher->photo))) {
            unlink(storage_path('app/public/' . $teacher->photo));
        }

        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Teacher Deleted Successfully'
        ]);
    }
}