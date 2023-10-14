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
        Schema::create('purchasing_invoices', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount');
            $table->decimal('paymented');
            $table->unsignedBigInteger('vendors_id')->nullable();
            $table->foreign('vendors_id')->references('id')->on('vendors')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchasing_invoices');
    }
};