<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskLevel extends Model
{
    protected $fillable = ['code', 'name', 'description', 'suggestion'];

    // Fungsi generate kode otomatis (H01, H02...)
    public static function generateCode()
    {
        $last = self::orderBy('id', 'DESC')->first();

        if (!$last) {
            return 'H01';
        }

        $lastCode = $last->code;
        $number   = (int) substr($lastCode, 1);
        $nextNumber = $number + 1;

        return 'H' . str_pad((string)$nextNumber, 2, '0', STR_PAD_LEFT);
    }
}