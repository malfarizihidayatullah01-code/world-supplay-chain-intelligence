<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherData extends Model
{
    use HasFactory;

    protected $table = 'weather_data';

    protected $fillable = [
        'country_id',
        'temperature',
        'humidity',
        'wind_speed',
        'weather_condition',
        'weather_description',
        'observation_time',
        'status',
    ];

    protected $casts = [
        'observation_time' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
