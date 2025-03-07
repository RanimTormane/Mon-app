<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleAnalytics extends Model
{
    use HasFactory;
    protected $table='google_analytics';
    //make sure that the fields are stored 
    protected $fillable=['date',',sessions','pageviews','users','avg_session_duration','bounce_rate','page','pageviews_per_session','new_visitors','returning_visitors','user_type'];
}
