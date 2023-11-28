<?php

namespace App\Models;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'details',
    ];
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
