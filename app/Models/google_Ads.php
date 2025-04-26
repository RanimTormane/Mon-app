<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class google_Ads extends Model
{
    use HasFactory;
    protected $table='google_ads';
    protected $fillable=[
        'api_id',
        'campaign_name',
        'product_name',
        'cost',
        'conversions',
        'conversion_value',
        'lead_type',
        'date'
    ];
    public function api()
    {
        return $this->belongsTo(API::class, 'api_id');
    }}
