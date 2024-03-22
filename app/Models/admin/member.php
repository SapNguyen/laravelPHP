<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class member extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "member";
    public $primaryKey = "mem_id";

    public function feedbacks()
    {
        return $this->hasMany(feedback::class, 'mem_id');
    }
}
