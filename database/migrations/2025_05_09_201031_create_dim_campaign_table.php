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
        Schema::create('dim_campaign', function (Blueprint $table) {
            $table->id('campaign_id');
            $table->string('campaign_name', 255);
            $table->string('product_name', 255)->nullable();
            $table->string('lead_type', 50)->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dim_campaign');
    }
};
