<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expenses extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function ExpensesTypes(): BelongsTo
    {
        return $this->belongsTo(ExpensesTypes::class, 'expenses_type_id');
    }
}