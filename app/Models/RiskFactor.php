<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskFactor extends Model
{
    protected $fillable = ['code', 'category', 'name', 'question_text', 'medical_explanation', 'recommendation'];

    // Fungsi generate kode otomatis (E01, E02...)
    public static function generateCode()
    {
        $last = self::orderBy('id', 'DESC')->first();

        if (!$last) {
            return 'E01';
        }

        $lastCode = $last->code;
        $number   = (int) substr($lastCode, 1);
        $nextNumber = $number + 1;

        return 'E' . str_pad((string)$nextNumber, 2, '0', STR_PAD_LEFT);
    }
}