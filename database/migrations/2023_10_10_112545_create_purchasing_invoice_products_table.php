<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchasing_invoice_products', function (Blueprint $table) {
            $table->id();
            $table->integer('qty');
            $table->decimal('purchase_price');
            $table->unsignedBigInteger('purchasing_invoices_id')->nullable();
            $table->foreign('purchasing_invoices_id')->references('id')->on('purchasing_invoices')->onDelete('set null');
            $table->unsignedBigInteger('purchase_products_id')->nullable();
            $table->foreign('purchase_products_id')->references('id')->on('purchase_products')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchasing_invoice_products');
    }
};