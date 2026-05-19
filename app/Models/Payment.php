<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'amount',
        'payment_type',
        'payment_date',
        'transaction_id',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}