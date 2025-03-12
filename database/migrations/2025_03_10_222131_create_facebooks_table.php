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
        Schema::create('facebooks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('liked_at')->nullable();
            $table->string('page_id');
            $table->string('name');
            $table->integer('page_views')->nullable();
            $table->integer('page_likes')->nullable();
            $table->integer('engagement')->nullable();
            $table->date('date'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facebooks', function (Blueprint $table) {
            $table->dropColumn('liked_at');
        });
    }
};
