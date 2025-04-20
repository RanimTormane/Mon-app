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
        Schema::create('trafic_stats', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // ex: 2025-03-01
            $table->integer('visiteurs_uniques');
            $table->integer('sessions');
            $table->bigInteger('temps_total_site'); // en secondes
            $table->float('bounce_rate', 5, 2); // ex: 37.84
            $table->integer('pages_vues_totales');
            $table->integer('nouveaux_visiteurs');
            $table->integer('visiteurs_recurrents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trafic_stats');
    }
};
