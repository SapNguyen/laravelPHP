<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class discount extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "discount";
    public $primaryKey = "discount_id";


    public function product()
    {
        return $this->belongsTo(product::class, 'discount_id');
    }
}
