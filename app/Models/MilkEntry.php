<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilkEntry extends Model
{
    protected $fillable = [
        'entry_date',
        'quantity_kg',
        'rate_per_kg',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];
}
