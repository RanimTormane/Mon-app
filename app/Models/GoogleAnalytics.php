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
        'api_id',
        'visitor_id',
        'session',
        'visit_date',
        'campaign_name',
        'traffic_source',
        'lead_type',
        'is_converted',
        'lead_id',
    ];
    public function api()
    {
        return $this->belongsTo(API::class, 'api_id');
    }}
