<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyRate extends Model
{
    protected $fillable = [
        'month',
        'year',
        'rate_per_kg',
        'is_active'
    ];
}
