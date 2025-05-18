<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class API extends Model
{
    use HasFactory;

    protected $table = 'api';
    protected $fillable = ['name','description','token','status'];

    public function posts(){
        return $this->hasMany(posts::class, 'api_id'); // relation inverse ,récupérer tous les posts liés à une API 
    }
    public function trafic(){
        return $this->hasMany(trafic_stats::class, 'trafic_stats'); // relation inverse ,récupérer tous les posts liés à une API 
    }
    public function googleAds(){
        return $this->hasMany(google_Ads::class,'google_ads');

    }
    public function googleAnalytics(){
        return $this->hasMany(GoogleAnalytics::class,'google_analytics');
        
    }


}