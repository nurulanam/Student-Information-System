<?php

namespace App\Models;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'std_id',
        'full_name',
        'phone',
        'email',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
