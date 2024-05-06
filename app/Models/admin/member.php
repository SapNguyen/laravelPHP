<?php

namespace App\Models\admin;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// class member extends Model
// {
//     use HasFactory;
//     public $timestamps = false;
//     protected $table = "member";
//     public $primaryKey = "mem_id";
//     protected $fillable = ['name', 'email', 'password', 'api_token'];

//     protected $hidden = ['password', 'api_token'];
//     protected $guarded = [];

//     public function getJWTIdentifier()
//     {
//         return $this->getKey();
//     }

//     public function getJWTCustomClaims()
//     {
//         return [];
//     }

//     public function feedbacks()
//     {
//         return $this->hasMany(feedback::class, 'mem_id');
//     }
// }

class member extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = "member";
    protected $primaryKey = "mem_id";
    protected $fillable = ['name', 'email', 'password', 'api_token'];

    protected $hidden = ['password', 'api_token'];
    protected $guarded = [];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'mem_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'mem_id')->where('order_status', 2);
    }
}
