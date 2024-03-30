<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "order";
    public $primaryKey = "order_id";

    public function details()
    {
        return $this->hasMany(product_order::class, 'order_id');
    }
    public function feedbacks()
    {
        return $this->hasMany(feedback::class, 'order_id');
    }
    public function members()
    {
        return $this->belongsTo(member::class, 'mem_id');
    }
}
