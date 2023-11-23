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
        Schema::create('avaliables', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('note');
            $table->decimal('dollaramount',64,2);
            $table->decimal('dinnaramount',64,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliables');
    }
};
