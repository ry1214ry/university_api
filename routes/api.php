<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard/statistics', [
        DashboardController::class,
        'statistics'
    ]);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('teachers', TeacherController::class);
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('attendances', AttendanceController::class);
    Route::apiResource('enrollments', EnrollmentController::class);
    Route::apiResource('results', ResultController::class);
    Route::apiResource('payments', PaymentController::class);

});