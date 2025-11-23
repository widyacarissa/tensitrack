<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TingkatRisiko extends Model
{
    use HasFactory;

    protected $table = 'tingkat_risiko';

    protected $fillable = [
        'kode',
        'tingkat_risiko',
        'keterangan',
        'saran',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tingkatRisiko) {
            $latestTingkatRisiko = static::latest('id')->first();
            $nextId = $latestTingkatRisiko ? $latestTingkatRisiko->id + 1 : 1;
            $tingkatRisiko->kode = 'H' . str_pad($nextId, 2, '0', STR_PAD_LEFT);
        });
    }

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
