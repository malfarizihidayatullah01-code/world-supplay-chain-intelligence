<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Http\Requests\StorePortRequest;
use App\Http\Requests\UpdatePortRequest;
use App\Services\PortService;
use Illuminate\Http\Request;

class PortController extends Controller
{
    protected $portService;

    public function __construct(PortService $portService)
    {
        $this->portService = $portService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'country_id', 'status', 'sort_by', 'sort_dir']);
        $ports = $this->portService->getPaginatedPorts($filters, 10);
        $countries = $this->portService->getAllCountries();
        
        return view('ports.index', compact('ports', 'filters', 'countries'));
    }

    public function create()
    {
        $countries = $this->portService->getAllCountries();
        return view('ports.create', compact('countries'));
    }

    public function store(StorePortRequest $request)
    {
        $this->portService->createPort($request->validated());
        return redirect()->route('ports.index')->with('success', 'Port created successfully.');
    }

    public function show(Port $port)
    {
        return view('ports.show', compact('port'));
    }

    public function edit(Port $port)
    {
        $countries = $this->portService->getAllCountries();
        return view('ports.edit', compact('port', 'countries'));
    }

    public function update(UpdatePortRequest $request, Port $port)
    {
        $this->portService->updatePort($port->id, $request->validated());
        return redirect()->route('ports.index')->with('success', 'Port updated successfully.');
    }

    public function destroy(Port $port)
    {
        $this->portService->deletePort($port->id);
        return redirect()->route('ports.index')->with('success', 'Port deleted successfully.');
    }
}
