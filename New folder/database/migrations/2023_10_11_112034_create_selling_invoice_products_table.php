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
        Schema::create('selling_invoice_products', function (Blueprint $table) {
            $table->id();
            $table->decimal('sallingPrice');
            $table->integer('qty');
            $table->integer('total')->default(0);
            $table->unsignedBigInteger('selling_products_id')->nullable();
            $table->foreign('selling_products_id')->references('id')->on('selling_products')->onDelete('set null');
            $table->unsignedBigInteger('selling_invoices_id')->nullable();
            $table->foreign('selling_invoices_id')->references('id')->on('selling_invoices')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selling_invoice_products');
    }
};