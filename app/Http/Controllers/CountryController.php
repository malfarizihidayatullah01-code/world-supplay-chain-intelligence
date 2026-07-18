<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Services\CountryService;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'region', 'status', 'sort_by', 'sort_dir']);
        $countries = $this->countryService->getPaginatedCountries($filters, 10);
        
        // Ensure regions are available for filter dropdown
        $regions = Country::select('region')->distinct()->pluck('region');
        
        return view('countries.index', compact('countries', 'filters', 'regions'));
    }

    public function create()
    {
        return view('countries.create');
    }

    public function store(StoreCountryRequest $request)
    {
        $this->countryService->createCountry($request->validated());
        return redirect()->route('countries.index')->with('success', 'Country created successfully.');
    }

    public function show(Country $country)
    {
        // Not specifically requested but good practice for resource controller
        return view('countries.show', compact('country'));
    }

    public function edit(Country $country)
    {
        return view('countries.edit', compact('country'));
    }

    public function update(UpdateCountryRequest $request, Country $country)
    {
        $this->countryService->updateCountry($country->id, $request->validated());
        return redirect()->route('countries.index')->with('success', 'Country updated successfully.');
    }

    public function destroy(Country $country)
    {
        $this->countryService->deleteCountry($country->id);
        return redirect()->route('countries.index')->with('success', 'Country deleted successfully.');
    }
}
