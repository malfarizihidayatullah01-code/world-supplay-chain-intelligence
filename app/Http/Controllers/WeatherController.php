<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use App\Jobs\SyncWeatherJob;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index()
    {
        $weatherData = $this->weatherService->getPaginated(10);
        return view('weather.index', compact('weatherData'));
    }

    public function sync()
    {
        // Dispatch the sync to the database queue so the browser never waits.
        // The queue worker (php artisan queue:work) processes this in background.
        SyncWeatherJob::dispatch();

        return redirect()->route('weather.index')
            ->with('success', 'Weather synchronization started in the background. Refresh in a few minutes to see updated data.');
    }
}
