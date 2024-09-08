<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    //public $timestamps = false;  // Corrected this line

    protected $fillable = [
        'user_id',  // Added user_id to fillable attributes
        'image',
        //'role',
        'title',
        'description'
    ];
}
