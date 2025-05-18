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
        Schema::create('fact_roas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('date_id');
            $table->decimal('total_conversion_value', 15, 2);
            $table->decimal('total_cost', 15, 2);
            $table->decimal('roas', 10, 2);
            $table->foreign('product_id')->references('product_id')->on('dim_product')->onDelete('cascade');
            $table->foreign('date_id')->references('date_id')->on('dim_date')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fact_roas');
    }
};
