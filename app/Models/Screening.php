<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    protected $fillable = [
        'user_id', 
        'client_name', 
        'snapshot_age', 
        'snapshot_height', 
        'snapshot_weight', 
        'snapshot_systolic', 
        'snapshot_diastolic', 
        'result_level', 
        'score'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(ScreeningDetail::class);
    }
}