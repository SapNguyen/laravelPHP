<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_order extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "product_order";
    public $primaryKey = "product_order_id";


    public function product()
    {
        return $this->belongsTo(product::class, 'product_id');
    }
}
