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
            $table->uuid('visitor_id'); // UUID for visitor
            $table->uuid('session'); // UUID for session
            $table->dateTime('visit_date'); // Date and time of visit
            $table->enum('campaign_name', ['Google Ads', 'Facebook Ads', 'Email Marketing', 'LinkedIn Ads', 'TikTok Ads']); // Campaign
            $table->enum('traffic_source', ['SEO', 'SEA', 'Social Media', 'Direct', 'Referral']); // Traffic source
            $table->enum('lead_type', ['Froid', 'Tiède', 'Chaud']); // Lead type
            $table->boolean('is_converted')->default(false); // Conversion status
            $table->uuid('lead_id')->nullable(); // Lead ID
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
