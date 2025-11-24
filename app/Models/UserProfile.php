<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profile';

    protected $fillable = [
        'user_id',
        'gender',
        'age',
        'weight',
        'height',
        'bmi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Calculate BMI based on weight and height
     * Formula: weight(kg) / (height(m))^2
     */
    public function calculateBMI()
    {
        if ($this->weight && $this->height) {
            $heightInMeters = $this->height / 100; // Convert cm to m
            $this->bmi = round($this->weight / ($heightInMeters * $heightInMeters), 2);
        }

        return $this;
    }

    /**
     * Get BMI category
     */
    public function getBMICategory()
    {
        if (! $this->bmi) {
            return null;
        }

        if ($this->bmi < 18.5) {
            return 'Underweight';
        } elseif ($this->bmi >= 18.5 && $this->bmi < 25) {
            return 'Normal';
        } elseif ($this->bmi >= 25 && $this->bmi < 30) {
            return 'Overweight';
        } else {
            return 'Obese';
        }
    }

    /**
     * Get BMI category color for UI
     */
    public function getBMICategoryColor()
    {
        $category = $this->getBMICategory();

        return match ($category) {
            'Underweight' => 'info',
            'Normal' => 'success',
            'Overweight' => 'warning',
            'Obese' => 'danger',
            default => 'secondary'
        };
    }
}
