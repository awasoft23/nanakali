<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employees extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function EmployeesAbsenses(): HasMany
    {
        return $this->hasMany(EmployeesAbsenses::class, 'employees_id');
    }
}