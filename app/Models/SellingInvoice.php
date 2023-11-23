<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SellingInvoice extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = ['images'=>'array'];
    public function Customers(): BelongsTo
    {
        return $this->belongsTo(Customers::class, 'customers_id');
    }

    public function SellingInvoiceProducts(): HasMany
    {
        return $this->hasMany(SellingInvoiceProducts::class, 'selling_invoices_id');
    }

}
