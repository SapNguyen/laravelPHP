<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class discount_product extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "discount_product";
}
