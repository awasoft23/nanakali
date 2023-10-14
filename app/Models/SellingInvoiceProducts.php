<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellingInvoiceProducts extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function SellingProducts(): BelongsTo
    {
        return $this->belongsTo(SellingProducts::class, 'selling_products_id');
    }
    public function SellingInvoice(): BelongsTo
    {
        return $this->belongsTo(SellingInvoice::class, 'selling_invoices_id');
    }
}