<?php

namespace App\Repositories;

use App\Models\Country;
use App\Models\Port;
use App\Models\ShippingRoute;
use App\Models\WeatherData;
use App\Models\ExchangeRate;
use App\Models\NewsArticle;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getCounts(): array
    {
        return [
            'countries' => Country::count(),
            'ports' => Port::count(),
            'shipping_routes' => ShippingRoute::count(),
            'weather_data' => WeatherData::count(),
            'exchange_rates' => ExchangeRate::count(),
            'news_articles' => NewsArticle::count(),
        ];
    }

    public function getLastSyncs(): array
    {
        return [
            'countries' => Country::max('updated_at'),
            'weather' => WeatherData::max('updated_at'),
            'exchange_rates' => ExchangeRate::max('updated_at'),
            'news' => NewsArticle::max('updated_at'),
        ];
    }

    public function getRecentData(): array
    {
        return [
            'weather' => WeatherData::with('country')->latest('updated_at')->take(5)->get(),
            'exchange_rates' => ExchangeRate::with('country')->latest('updated_at')->take(5)->get(),
            'news' => NewsArticle::with('country')->latest('updated_at')->take(5)->get(),
        ];
    }
}
