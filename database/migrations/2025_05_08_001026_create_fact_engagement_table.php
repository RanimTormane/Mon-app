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
        Schema::create('fact_engagement', function (Blueprint $table) {
            $table->id();
            $table->integer('client_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('date_id');
            $table->decimal('engagement_kpi', 10, 2);
            $table->integer('like_count');
            $table->integer('comments_count');
            $table->integer('shares_count');
            $table->integer('impressions');
            $table->foreign('post_id')->references('post_id')->on('dim_post')->onDelete('cascade');
            $table->foreign('date_id')->references('date_id')->on('dim_date')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fact_engagement');
    }
};
