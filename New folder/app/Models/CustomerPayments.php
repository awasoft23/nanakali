<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPayments extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function Customers(): BelongsTo
    {
        return $this->belongsTo(Customers::class, 'customers_id');
    }

}