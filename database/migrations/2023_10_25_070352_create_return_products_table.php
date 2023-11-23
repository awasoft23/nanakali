<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('return_products', function (Blueprint $table) {
            $table->id();
            $table->decimal('sallingPrice');
            $table->integer('qty');
            $table->decimal('total')->default(0);
            $table->integer('status');
            $table->unsignedBigInteger('selling_products_id')->nullable();
            $table->foreign('selling_products_id')->references('id')->on('selling_products')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_products');
    }
};
