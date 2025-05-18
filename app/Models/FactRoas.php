<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactRoas extends Model
{
    use HasFactory;
    protected $table = 'fact_roas';
    protected $fillable = ['product_id', 'date_id', 'total_conversion_value', 'total_cost', 'roas'];

    // Relation avec Dim_Product
    public function product()
    {
        return $this->belongsTo(DimProduct::class, 'product_id', 'product_id');
    }

    // Relation avec Dim_Date
    public function date()
    {
        return $this->belongsTo(DimDate::class, 'date_id', 'date_id');
    }
}
