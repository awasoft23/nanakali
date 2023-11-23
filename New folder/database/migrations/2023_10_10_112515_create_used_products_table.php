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
        Schema::create('used_products', function (Blueprint $table) {
            $table->id();
            $table->decimal('qty', 30, 2);
            $table->unsignedBigInteger('purchase_products_id')->nullable();
            $table->foreign('purchase_products_id')->references('id')->on('purchase_products')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('used_products');
    }
};