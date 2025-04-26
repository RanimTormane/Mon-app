<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    use HasFactory;
    protected $table = 'instagram_account';
    protected $fillable = [
        'instagram_id',
        'username',
        'profile_picture_url',
        'dashboards', 
    ];

   

    public function posts()
    {
        return $this->hasMany(posts::class, 'client_id');
        
    }
    public function KPIs(){
        return $this->hasMany(KPIs::class, 'client_id');
        
    }
}
