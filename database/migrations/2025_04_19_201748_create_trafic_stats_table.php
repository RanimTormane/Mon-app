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
            $table->foreignId('api_id')->constrained('api')->onDelete('cascade');
            $table->date('date'); 
            $table->integer('visiteurs_uniques');
            $table->integer('sessions');
            $table->bigInteger('temps_total_site'); 
            $table->float('bounce_rate', 5, 2);
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
