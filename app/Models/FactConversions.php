<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactConversions extends Model
{
    use HasFactory;
    protected $table = 'fact_conversions';
    protected $fillable = ['campaign_id', 'date_id', 'total', 'converted', 'conversion_rate'];

    public function campaign()
    {
        return $this->belongsTo(DimCampaign::class, 'campaign_id','campaign_id');
    }

    public function date()
    {
        return $this->belongsTo(DimDate::class, 'date_id','date_id');
    }
}
