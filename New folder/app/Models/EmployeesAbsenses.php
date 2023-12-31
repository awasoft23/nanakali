<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeesAbsenses extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function Employees(): BelongsTo
    {
        return $this->belongsTo(Employees::class, 'employees_id');
    }
}