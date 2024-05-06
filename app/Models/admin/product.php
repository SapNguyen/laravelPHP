<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "product";
    public $primaryKey = "product_id";

    public function details()
    {
        return $this->hasMany(product_size_color::class, 'product_id');
    }
    public function discounts()
    {
        return $this->belongsTo(discount::class, 'discount_id');
    }
    public function feedbacks()
    {
        return $this->hasMany(feedback::class, 'product_id');
    }
    public function brands()
    {
        return $this->belongsTo(brand::class, 'brand_id');
    }
}
