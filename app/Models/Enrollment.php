<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ] ;
}
