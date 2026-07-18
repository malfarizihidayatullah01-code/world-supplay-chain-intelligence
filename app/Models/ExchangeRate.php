<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $table = 'exchange_rates';

    protected $fillable = [
        'country_id',
        'base_currency',
        'target_currency',
        'exchange_rate',
        'exchange_date',
        'status',
    ];

    protected $casts = [
        'exchange_date' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
