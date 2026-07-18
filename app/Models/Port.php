<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'port_code',
        'port_name',
        'city',
        'latitude',
        'longitude',
        'status',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function originRoutes()
    {
        return $this->hasMany(ShippingRoute::class, 'origin_port_id');
    }

    public function destinationRoutes()
    {
        return $this->hasMany(ShippingRoute::class, 'destination_port_id');
    }
}
