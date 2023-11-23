<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnProducts extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function SellingProducts(): BelongsTo
    {
        return $this->belongsTo(SellingProducts::class, 'selling_products_id');
    }
    public function Customers(): BelongsTo
    {
        return $this->belongsTo(Customers::class, 'customers_id');
    }
}
