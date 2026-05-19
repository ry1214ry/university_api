<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Department;
use App\Models\Course;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function statistics()
    {
        $totalStudents = Student::count();

        $totalTeachers = Teacher::count();

        $totalDepartments = Department::count();

        $totalCourses = Course::count();

        $totalRevenue = Payment::where('status', 'paid')
            ->sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'students' => $totalStudents,
                'teachers' => $totalTeachers,
                'departments' => $totalDepartments,
                'courses' => $totalCourses,
                'revenue' => $totalRevenue
            ]
        ]);
    }
}