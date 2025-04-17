<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleAnalytics extends Model
{
    use HasFactory;
    protected $table='google_analytics';
    //make sure that the fields are stored 
    protected $fillable = [
        'visitor_id',
        'session',
        'visit_date',
        'campaign_name',
        'traffic_source',
        'lead_type',
        'is_converted',
        'lead_id',
    ];
}
