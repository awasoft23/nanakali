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
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount');
            $table->string('note');
            $table->unsignedBigInteger('customers_id')->nullable();
            $table->unsignedBigInteger('selling_invoices_id')->nullable();
            $table->foreign('customers_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('selling_invoices_id')->references('id')->on('selling_invoices')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_payments');
    }
};