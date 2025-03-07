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
        Schema::create('google_analytics', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('sessions');//actions made by users on website 
            $table->integer('pageviews');
            $table->integer('users');
            $table->float('avg_session_duration');
            $table->float('bounce_rate');
            $table->float ('page');
            $table->float('pageviews_per_session');  
            $table->integer('new_visitors');  
            $table->integer('returning_visitors');  
            $table->string('user_type');  // (new or recurring )
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_analytics');
    }
};
