<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class receipt_product extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "receipt_product";
}
