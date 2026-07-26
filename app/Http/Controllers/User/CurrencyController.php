<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Services\Api\CurrencyApiService;
use App\Services\Currency\CurrencyInsightService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    protected $currencyApiService;
    protected $currencyInsightService;

    public function __construct(CurrencyApiService $currencyApiService, CurrencyInsightService $currencyInsightService) {
        $this->currencyApiService = $currencyApiService;
        $this->currencyInsightService = $currencyInsightService;
    }

    public function index(Request $request) {
        $search = $request->query('search');
        $currencies = $this->currencyApiService->getAllCurrencies($search);
        
        $selectedCurrency = null;
        if ($request->has('currency')) {
            $selectedCurrency = $currencies->where('currency_code', strtoupper($request->query('currency')))->first();
        }
        if (!$selectedCurrency && $currencies->isNotEmpty()) {
            $selectedCurrency = $currencies->first();
        }

        $baseCurrency = null;
        if ($request->has('base')) {
            $baseCurrency = $currencies->where('currency_code', strtoupper($request->query('base')))->first();
        }
        if (!$baseCurrency) {
            $baseCurrency = $currencies->where('currency_code', 'IDR')->first();
        }
        if (!$baseCurrency && $currencies->isNotEmpty()) {
            $baseCurrency = $currencies->where('currency_code', 'USD')->first();
        }

        $topCurrencies = $this->currencyApiService->getTopCurrencies();
        
        $baseRate = $baseCurrency ? (float) $baseCurrency->exchange_rate_usd : 1.0;

        foreach ($topCurrencies as $top) {
            $targetRate = (float) $top->exchange_rate_usd;
            $top->converted_rate = $targetRate > 0 ? ($baseRate / $targetRate) : 0;
        }

        $history = collect();
        if ($selectedCurrency) {
            $history = \App\Models\CurrencyCache::where('currency_code', $selectedCurrency->currency_code)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        $dailyChange = 0.0;
        $weeklyChange = 0.0;
        $monthlyChange = 0.0;
        $historicalLabels = [];
        $historicalData = [];

        if ($history->count() > 1 && $baseCurrency) {
            $baseHistory = \App\Models\CurrencyCache::where('currency_code', $baseCurrency->currency_code)
                ->orderBy('created_at', 'asc')
                ->get()
                ->keyBy(function($item) { return $item->created_at->format('Y-m-d'); });

            $latestConverted = 0;
            $yesterdayConverted = 0;
            $lastWeekConverted = 0;

            foreach ($history as $record) {
                $dateKey = $record->created_at->format('Y-m-d');
                $histBaseRate = isset($baseHistory[$dateKey]) ? (float) $baseHistory[$dateKey]->exchange_rate_usd : $baseRate;
                
                $targetRate = (float) $record->exchange_rate_usd;
                $convertedRate = $targetRate > 0 ? ($histBaseRate / $targetRate) : 0;

                $historicalLabels[] = $record->created_at->format('M d');
                $historicalData[] = $convertedRate;
            }

            $latestConverted = end($historicalData);
            
            if (count($historicalData) >= 2) {
                $yesterdayConverted = $historicalData[count($historicalData) - 2];
                $dailyChange = (($latestConverted - $yesterdayConverted) / $yesterdayConverted) * 100;
            }

            if (count($historicalData) >= 7) {
                $lastWeekConverted = $historicalData[count($historicalData) - 7];
                $weeklyChange = (($latestConverted - $lastWeekConverted) / $lastWeekConverted) * 100;
            }

            // Limit chart to last 7 days
            $historicalLabels = array_slice($historicalLabels, -7);
            $historicalData = array_slice($historicalData, -7);
        }

        $insight = $this->currencyInsightService->generateInsight($selectedCurrency, $dailyChange);

        return view('user.currency', compact('currencies', 'selectedCurrency', 'baseCurrency', 'topCurrencies', 'insight', 'search', 'dailyChange', 'weeklyChange', 'monthlyChange', 'historicalLabels', 'historicalData', 'baseRate'));
    }
}
