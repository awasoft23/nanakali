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
        Schema::create('vendor_payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount');
            $table->string('note');
            $table->unsignedBigInteger('vendors_id')->nullable();
            $table->foreign('vendors_id')->references('id')->on('vendors')->onDelete('set null');
            $table->unsignedBigInteger('purchasing_invoices_id')->nullable();
            $table->foreign('purchasing_invoices_id')->references('id')->on('purchasing_invoices')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_payments');
    }
};