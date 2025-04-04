<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KPIs extends Model
{
    use HasFactory;
    protected $table = 'kpi';
    protected $fillable = ['name','value','trend','status','actions'];
}
