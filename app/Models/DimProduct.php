<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DimProduct extends Model
{
    use HasFactory;
    protected $table = 'dim_product';
    protected $primaryKey = 'product_id';
    protected $fillable = ['product_name'];

    // Relation avec Fact_ROAS
    public function roas()
    {
        return $this->hasMany(FactRoas::class, 'product_id', 'product_id');
    }
}
