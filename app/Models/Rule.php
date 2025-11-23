<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    protected $table = 'rule';

    protected $fillable = [
        'tingkat_risiko_id',
        'faktor_risiko_id',
    ];

    public function tingkatRisiko()
    {
        return $this->belongsTo(TingkatRisiko::class, 'tingkat_risiko_id');
    }

    public function faktorRisiko()
    {
        return $this->belongsTo(FaktorRisiko::class, 'faktor_risiko_id');
    }

    public function nextFaktorRisiko()
    {
        return $this->belongsTo(FaktorRisiko::class, 'next_first_faktor_risiko_id');
    }
}
