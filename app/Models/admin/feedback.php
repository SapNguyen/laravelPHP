<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class feedback extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "feedback";
    public $primaryKey = "feedback_id";


    public function product()
    {
        return $this->belongsTo(product::class, 'product_id');
    }

    public function member()
    {
        return $this->belongsTo(member::class, 'mem_id');
    }
}
