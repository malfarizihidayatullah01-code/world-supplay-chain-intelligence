<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_name',
        'iso2_code',
        'iso3_code',
        'capital_city',
        'region',
        'sub_region',
        'currency_code',
        'currency_name',
        'latitude',
        'longitude',
        'flag_url',
        'status',
    ];

    public function ports()
    {
        return $this->hasMany(Port::class);
    }

    public function weatherData()
    {
        return $this->hasMany(WeatherData::class);
    }

    public function exchangeRates()
    {
        return $this->hasMany(ExchangeRate::class);
    }

    public function newsArticles()
    {
        return $this->hasMany(NewsArticle::class);
    }
}
