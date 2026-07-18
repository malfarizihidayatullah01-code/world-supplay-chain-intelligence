<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\CountryController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\ShippingRouteController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RiskAnalysisController;
use App\Http\Controllers\ShipmentAnalysisController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\RouteRiskAnalysisController;
use App\Http\Controllers\ShipmentRiskAnalysisController;
use App\Http\Controllers\ShipmentRecommendationController;
use App\Http\Controllers\ShipmentMonitoringController;
use App\Http\Controllers\AdministrationController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::middleware([\App\Http\Middleware\IsAdmin::class])->group(function () {
        Route::resource('countries', CountryController::class);
        Route::resource('ports', PortController::class);
        Route::resource('shipping-routes', ShippingRouteController::class);
        Route::resource('shipments', ShipmentController::class);

        // Route Risk Analysis
        Route::prefix('route-risk-analysis')->name('route-risk-analysis.')->group(function () {
            Route::get('/',                     [RouteRiskAnalysisController::class, 'index'])->name('index');
            Route::get('/{id}',                 [RouteRiskAnalysisController::class, 'show'])->name('show');
            Route::post('/analyse/{shipmentId}',[RouteRiskAnalysisController::class, 'analyse'])->name('analyse');
            Route::post('/analyse-all',         [RouteRiskAnalysisController::class, 'analyseAll'])->name('analyse-all');
        });

        // Shipment Risk Analysis
        Route::prefix('shipment-risk-analysis')->name('shipment-risk-analysis.')->group(function () {
            Route::get('/',                     [ShipmentRiskAnalysisController::class, 'index'])->name('index');
            Route::get('/{id}',                 [ShipmentRiskAnalysisController::class, 'show'])->name('show');
            Route::post('/analyse/{shipmentId}',[ShipmentRiskAnalysisController::class, 'analyse'])->name('analyse');
            Route::post('/analyse-all',         [ShipmentRiskAnalysisController::class, 'analyseAll'])->name('analyse-all');
        });

        // Shipment Recommendations
        Route::prefix('shipment-recommendations')->name('shipment-recommendations.')->group(function () {
            Route::get('/',                     [ShipmentRecommendationController::class, 'index'])->name('index');
            Route::get('/{id}',                 [ShipmentRecommendationController::class, 'show'])->name('show');
            Route::post('/analyse/{shipmentId}',[ShipmentRecommendationController::class, 'analyse'])->name('analyse');
            Route::post('/analyse-all',         [ShipmentRecommendationController::class, 'analyseAll'])->name('analyse-all');
        });

        // Shipment Monitoring
        Route::prefix('shipment-monitoring')->name('shipment-monitoring.')->group(function () {
            Route::get('/',                     [ShipmentMonitoringController::class, 'index'])->name('index');
            Route::get('/{id}/detail',          [ShipmentMonitoringController::class, 'show'])->name('show');
        });
        
        Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
        Route::post('/weather/sync', [WeatherController::class, 'sync'])->name('weather.sync');
        
        Route::get('/currency', [CurrencyController::class, 'index'])->name('currency.index');
        Route::post('/currency/sync', [CurrencyController::class, 'sync'])->name('currency.sync');
        
        Route::get('/news', [NewsController::class, 'index'])->name('news.index');
        Route::post('/news/sync', [NewsController::class, 'sync'])->name('news.sync');
        Route::get('/risk-analysis', [RiskAnalysisController::class, 'index'])->name('risk-analysis.index');
        Route::get('/shipment-analysis', [ShipmentAnalysisController::class, 'index'])->name('shipment-analysis.index');
        Route::get('/administration', [AdministrationController::class, 'index'])->name('administration.index');
    });
});
