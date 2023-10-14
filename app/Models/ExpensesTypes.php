<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpensesTypes extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function Expenses(): HasMany
    {
        return $this->hasMany(Expenses::class, 'expenses_type_id');
    }
}