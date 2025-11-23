<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TingkatRisiko extends Model
{
    use HasFactory;

    protected $table = 'tingkat_risiko';

    protected $fillable = [
        'name',
        'reason',
        'solution',
        'image',
    ];

    public function faktorRisiko()
    {
        return $this->belongsToMany(FaktorRisiko::class, 'rule', 'tingkat_risiko_id', 'faktor_risiko_id');
    }

    public function rule()
    {
        return $this->hasMany(Rule::class);
    }

    public function diagnosis()
    {
        return $this->hasMany(Diagnosis::class);
    }
}
