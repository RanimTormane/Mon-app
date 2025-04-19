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
        Schema::create('google_ads', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_name');
            $table->string('product_name');
            $table->decimal('cost', 10, 2);
            $table->integer('conversions');
            $table->decimal('conversion_value', 10, 2);
            $table->enum('lead_type', ['Froid', 'Tiède', 'Chaud']);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google__ads');
    }
};
