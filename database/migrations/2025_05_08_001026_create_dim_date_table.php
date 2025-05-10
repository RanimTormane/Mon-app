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
        Schema::create('dim_date', function (Blueprint $table) {
            $table->id('date_id'); // Utilise id() pour AUTO_INCREMENT
            $table->integer('day');
            $table->integer('month');
            $table->integer('year');
            $table->date('full_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dim_date');
    }
};
