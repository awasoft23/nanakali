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
        Schema::create('selling_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customers_id')->nullable();
            $table->foreign('customers_id')->references('id')->on('customers')->onDelete('set null');
            $table->decimal('amount', 30, 2)->default(0);
            $table->decimal('paymented', 30, 2);
            $table->integer('status')->nullable();
            $table->string('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selling_invoices');
    }
};