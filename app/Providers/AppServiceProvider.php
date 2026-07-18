<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\CountryRepositoryInterface::class,
            \App\Repositories\CountryRepository::class
        );
        $this->app->bind(
            \App\Repositories\PortRepositoryInterface::class,
            \App\Repositories\PortRepository::class
        );
        $this->app->bind(
            \App\Repositories\ShippingRouteRepositoryInterface::class,
            \App\Repositories\ShippingRouteRepository::class
        );
        $this->app->bind(
            \App\Repositories\WeatherRepositoryInterface::class,
            \App\Repositories\WeatherRepository::class
        );
        $this->app->bind(
            \App\Repositories\ExchangeRateRepositoryInterface::class,
            \App\Repositories\ExchangeRateRepository::class
        );
        $this->app->bind(
            \App\Repositories\NewsRepositoryInterface::class,
            \App\Repositories\NewsRepository::class
        );
        $this->app->bind(
            \App\Repositories\DashboardRepositoryInterface::class,
            \App\Repositories\DashboardRepository::class
        );
        $this->app->bind(
            \App\Repositories\ShipmentRepositoryInterface::class,
            \App\Repositories\ShipmentRepository::class
        );
        $this->app->bind(
            \App\Repositories\RouteRiskAnalysisRepositoryInterface::class,
            \App\Repositories\RouteRiskAnalysisRepository::class
        );
        $this->app->bind(
            \App\Repositories\ShipmentRiskAnalysisRepositoryInterface::class,
            \App\Repositories\ShipmentRiskAnalysisRepository::class
        );
        $this->app->bind(
            \App\Repositories\ShipmentRecommendationRepositoryInterface::class,
            \App\Repositories\ShipmentRecommendationRepository::class
        );
        $this->app->bind(
            \App\Repositories\ShipmentMonitoringRepositoryInterface::class,
            \App\Repositories\ShipmentMonitoringRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
