<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id', 
        'full_name', 
        'age', 
        'gender', 
        'height', 
        'weight', 
        'systolic', 
        'diastolic'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}