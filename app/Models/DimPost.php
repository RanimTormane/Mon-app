<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimPost extends Model
{
    use HasFactory;
    protected $table = 'dim_post';
    protected $fillable = ['post_id', 'caption', 'timestamp'];
}
