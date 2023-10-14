<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseProducts extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function UsedProducts(): HasMany
    {
        return $this->hasMany(UsedProducts::class, 'purchase_products_id');
    }
    public function PurchasingInvoiceProducts(): HasMany
    {
        return $this->hasMany(PurchasingInvoiceProducts::class, 'purchase_products_id');
    }
}