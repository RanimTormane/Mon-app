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
        Schema::create('instagram_account', function (Blueprint $table) {
            $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('instagram_id')->unique();
            $table->string('username');
            $table->text('profile_picture_url')->nullable();
            //$table->unsignedBigInteger('api_id'); // clé étrangère

            //$table->foreign('api_id')->references('id')->on('apis')->onDelete('cascade');

            $table->text('dashboards')->nullable();
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
