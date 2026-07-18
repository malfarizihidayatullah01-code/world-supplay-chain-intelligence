<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExchangeRateService;

class CurrencyController extends Controller
{
    protected $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    public function index()
    {
        $exchangeRates = $this->exchangeRateService->getPaginated(10);
        return view('currency.index', compact('exchangeRates'));
    }

    public function sync()
    {
        $result = $this->exchangeRateService->syncExchangeRates();
        
        if ($result['success']) {
            return redirect()->route('currency.index')->with('success', $result['message']);
        }
        
        return redirect()->route('currency.index')->with('error', $result['message']);
    }
}
