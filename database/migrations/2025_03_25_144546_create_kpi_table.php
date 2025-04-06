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
        Schema::create('kpi', function (Blueprint $table) {
            $table->id();
           $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('cascade');

            $table->string('name'); 
            $table->decimal('value', 8, 2); 
            $table->string('trend');
            $table->string('status');
             
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi');
    }
};
