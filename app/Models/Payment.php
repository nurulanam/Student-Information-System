<?php

namespace App\Models;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        "enrollment_id",
        "is_installment",
        "upfront_payment_amount",
        "installment_number",
        "amount_paid",
        "payment_type",
        "notes",
    ] ;

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
