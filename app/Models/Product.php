<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'supplier_id',
        'purchase_price',
        'sale_price',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getMarginMoneyAttribute()
    {
        return $this->sale_price - $this->purchase_price;
    }

    public function getMarginPercentAttribute()
    {
        if ($this->purchase_price == 0) {
            return 0;
        }
        
        return (($this->sale_price - $this->purchase_price) / $this->purchase_price) * 100;
    }
}
