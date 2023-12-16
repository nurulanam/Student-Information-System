<?php

namespace App\Models;

use App\Models\Payment;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;
    protected $fillable = [
        "student_id",
        "program_id",
        "total_cost",
        "payment_mode",
        "total_installment",
        "installment_completed",
        "status",
        "due_dates",
        "notes",
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
