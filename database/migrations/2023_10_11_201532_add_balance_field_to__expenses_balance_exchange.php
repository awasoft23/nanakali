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
        Schema::table('expenses_balance_exchanges', function (Blueprint $table) {
            $table->unsignedBigInteger('expenses_balances_id')->default(1)->nullable();
            $table->foreign('expenses_balances_id')->references('id')->on('expenses_balances')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses_balance_exchanges', function (Blueprint $table) {
            //
        });
    }
};