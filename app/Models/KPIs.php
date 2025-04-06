<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KPIs extends Model
{
    use HasFactory;
    protected $table = 'kpi';
    protected $fillable = ['client_id','name','value','trend','status'];
    public function client(){
        return $this->belongTo(Clients::class,'client_id');
    }
}
