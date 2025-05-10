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
        Schema::create('fact_conversions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('date_id');
            $table->integer('total');
            $table->integer('converted');
            $table->decimal('conversion_rate', 5, 2);
            $table->foreign('campaign_id')->references('campaign_id')->on('dim_campaign')->onDelete('cascade');$table->foreign('date_id')->references('date_id')->on('dim_date')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fact_conversions');
    }
};
