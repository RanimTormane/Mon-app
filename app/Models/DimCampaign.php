<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimCampaign extends Model
{
    use HasFactory;
    protected $table = 'dim_campaign';
    protected $primaryKey = 'campaign_id';
    protected $fillable = ['campaign_name', 'product_name', 'lead_type'];
    public $timestamps = false; // Désactive created_at et updated_at

}
