<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class API extends Model
{
    use HasFactory;

    protected $table = 'api';
    protected $fillable = ['name','description','token','status','actions'];

    public function posts(){
        return $this->hasMany(posts::class, 'api_id'); // relation inverse ,récupérer tous les posts liés à une API 
}
}