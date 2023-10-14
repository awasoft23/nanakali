<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SellingProducts extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function SellingInvoiceProducts(): HasMany
    {
        return $this->hasMany(SellingInvoiceProducts::class, 'selling_products_id');
    }
}