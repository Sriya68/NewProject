<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{

    protected $table = 'users_otp';
    public $timestamps="false";
    
    // protected $fillable = [
    //     'user_id',
    //     'otp',
    //     'is_verified'
    // ];
    protected $fillable = ['phone', 'otp', 'is_verified'];

    // Optionally, add a cast for `is_verified` if you want it as a boolean
    // protected $casts = [
    //     'is_verified' => 'boolean',
    // ];
}
