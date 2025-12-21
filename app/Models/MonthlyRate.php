<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyRate extends Model
{
    protected $fillable = [
        'effective_from',
        'rate_per_kg',
        'is_active'
    ];

    protected $casts = [
        'effective_from' => 'date',
        'is_active' => 'boolean',
    ];
}
