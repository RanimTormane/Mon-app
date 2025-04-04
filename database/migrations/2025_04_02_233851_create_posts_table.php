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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
           
        
           
            //$table->foreignId('client_id')->constrained('clients')->onDelete('cascade');


            $table->string('post_id');
            $table->string('caption');
            $table->integer('like_count');
            $table->integer('comments_count');
            $table->integer('engagement');
            $table->integer('shares_count');
            $table->bigInteger('impressions')->default(0);   
            $table->timestamp('timestamp');
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
