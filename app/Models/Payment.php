<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        "enrollment_id",
        "installment_number",
        "amount_paid",
        "payment_type",
        "notes",
    ] ;
}
