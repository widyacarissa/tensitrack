<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaktorRisiko extends Model
{
    use HasFactory;

    protected $table = 'faktor_risiko';

    protected $fillable = [
        'kode',
        'name',
        'tipe',
    ];
}
