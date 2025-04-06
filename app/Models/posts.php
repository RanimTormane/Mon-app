<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class posts extends Model
{
    use HasFactory;
    protected $fillable = [
        'api_id','client_id','post_id', 'caption', 'like_count', 'comments_count', 'shares_count','impressions','engagement','timestamp',
    ];
    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_id');// lien entre client_id de posts et l'id de clients
        return $this->belongsTo(API::class, 'api_id');
    }
}
