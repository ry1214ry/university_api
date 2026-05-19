<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'teacher_id',
        'first_name',
        'last_name',
        'gender',
        'email',
        'phone',
        'specialization',
        'salary',
        'address',
        'photo',
        'department_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}