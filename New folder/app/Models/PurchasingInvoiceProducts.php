<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchasingInvoiceProducts extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function PurchaseProducts(): BelongsTo
    {
        return $this->belongsTo(PurchaseProducts::class, 'purchase_products_id');
    }
    public function PurchasingInvoice(): BelongsTo
    {
        return $this->belongsTo(PurchasingInvoice::class, 'purchasing_invoices_id');
    }
}