<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class API extends Model
{
    use HasFactory;
    #rename the table name
    protected $table = 'api';
    protected $fillable = ['name','description','token','status','actions'];
}
