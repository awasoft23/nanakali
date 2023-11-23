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
        Schema::create('money_exchanges', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->default(1);
            $table->decimal('dollarAmount');
            $table->decimal('dinnarAmmount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('money_exchanges');
    }
};
