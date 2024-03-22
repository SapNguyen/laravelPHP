<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cart extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "cart";
    public $primaryKey = "cart_id";

    public function products()
    {
        return $this->belongsTo(product::class, 'product_id');
    }
    public function members()
    {
        return $this->belongsTo(member::class, 'mem_id');
    }
}
