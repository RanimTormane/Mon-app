<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimDate extends Model
{
    use HasFactory;
    protected $table = 'dim_date';
    protected $primaryKey = 'post_id';
    protected $fillable = ['day', 'month', 'year', 'full_date'];
}
