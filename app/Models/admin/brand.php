<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class brand extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "Brand";
    public $primaryKey = "brand_id";
}
