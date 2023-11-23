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
        Schema::table('purchasing_invoices', function (Blueprint $table) {
            Schema::table('purchasing_invoices', function (Blueprint $table) {
                $table->decimal('dolarPrice');
            });
            Schema::table('selling_invoices', function (Blueprint $table) {
                $table->decimal('dolarPrice');
            });
            Schema::table('vendor_payments', function (Blueprint $table) {
                $table->decimal('dolarPrice');
            });
            Schema::table('customer_payments', function (Blueprint $table) {
                $table->decimal('dolarPrice');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchasing_invoices', function (Blueprint $table) {
            //
        });
    }
};