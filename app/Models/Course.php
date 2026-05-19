<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_name',
        'course_code',
        'credit',
        'semester',
        'department_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}