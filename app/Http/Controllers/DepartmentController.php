<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    
    public function index(Request $request)
    {
        $search = $request->search;

        $departments = Department::when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('code', 'LIKE', "%{$search}%");
            })
            ->withCount(['students', 'teachers', 'courses'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Department List',
            'data' => $departments
        ], 200);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments',
            'description' => 'nullable|string'
        ]);

        $department = Department::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Department Created Successfully',
            'data' => $department
        ], 201);
    }

    
    public function show(string $id)
    {
        $department = Department::with([
            'students',
            'teachers',
            'courses'
        ])->find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Department Not Found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Department Detail',
            'data' => $department
        ], 200);
    }

    /**
     * Update department
     */
    public function update(Request $request, string $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Department Not Found'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code,' . $id,
            'description' => 'nullable|string'
        ]);

        $department->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Department Updated Successfully',
            'data' => $department
        ], 200);
    }

    public function destroy(string $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Department Not Found'
            ], 404);
        }

        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department Deleted Successfully'
        ], 200);
    }
}