<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class trafic_stats extends Model
{
    use HasFactory;
    protected $table='trafic_stats';
    protected $fillable=[
        'api_id',
        'date',
        'visiteurs_uniques',
        'sessions',
        'temps_total_site',
        'bounce_rate',
        'pages_vues_totales',
        'nouveaux_visiteurs',
        'visiteurs_recurrents'
    ];
    public function api(){
        return $this->belongsTo(API::class, 'api');
    }
}
