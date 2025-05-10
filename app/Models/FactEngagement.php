<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactEngagement extends Model
{
    use HasFactory;
    protected $table = 'fact_engagement';
    protected $fillable = ['client_id', 'post_id', 'date_id', 'engagement_kpi', 'like_count', 'comments_count', 'shares_count', 'impressions'];
    public function post()
    {
        return $this->belongsTo(DimPost::class, 'post_id', 'post_id');
       
    }
    public function date()
    {
        return $this->belongsTo(DimDate::class, 'date_id', 'date_id');
       
    }
}
