<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * method that modify the data base ,add table,columns,indexes
     */
    public function up(): void
    {
        Schema::create('api', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->text('description')->nullable();
            $table->string('token',100);
            $table->boolean('status')->default(0);
            $table->string('actions')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * inverse to up method that removing columns, deleting tables...
     */
    public function down(): void
    {
        Schema::dropIfExists('api');
    }
};
