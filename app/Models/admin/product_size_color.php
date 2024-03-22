<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_size_color extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "product_size_color";
    public $primaryKey = "product_size_color_id";


    public function product()
    {
        return $this->belongsTo(product::class, 'product_id');
    }
}
