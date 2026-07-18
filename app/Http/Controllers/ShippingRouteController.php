<?php

namespace App\Http\Controllers;

use App\Models\ShippingRoute;
use App\Http\Requests\StoreShippingRouteRequest;
use App\Http\Requests\UpdateShippingRouteRequest;
use App\Services\ShippingRouteService;
use Illuminate\Http\Request;

class ShippingRouteController extends Controller
{
    protected $shippingRouteService;

    public function __construct(ShippingRouteService $shippingRouteService)
    {
        $this->shippingRouteService = $shippingRouteService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'origin_port_id', 'destination_port_id', 'status', 'sort_by', 'sort_dir']);
        $shippingRoutes = $this->shippingRouteService->getPaginatedShippingRoutes($filters, 10);
        $ports = $this->shippingRouteService->getAllPorts();
        
        return view('shipping_routes.index', compact('shippingRoutes', 'filters', 'ports'));
    }

    public function create()
    {
        $ports = $this->shippingRouteService->getAllPorts();
        return view('shipping_routes.create', compact('ports'));
    }

    public function store(StoreShippingRouteRequest $request)
    {
        $this->shippingRouteService->createShippingRoute($request->validated());
        return redirect()->route('shipping-routes.index')->with('success', 'Shipping Route created successfully.');
    }

    public function show(ShippingRoute $shippingRoute)
    {
        return view('shipping_routes.show', compact('shippingRoute'));
    }

    public function edit(ShippingRoute $shippingRoute)
    {
        $ports = $this->shippingRouteService->getAllPorts();
        return view('shipping_routes.edit', compact('shippingRoute', 'ports'));
    }

    public function update(UpdateShippingRouteRequest $request, ShippingRoute $shippingRoute)
    {
        $this->shippingRouteService->updateShippingRoute($shippingRoute->id, $request->validated());
        return redirect()->route('shipping-routes.index')->with('success', 'Shipping Route updated successfully.');
    }

    public function destroy(ShippingRoute $shippingRoute)
    {
        $this->shippingRouteService->deleteShippingRoute($shippingRoute->id);
        return redirect()->route('shipping-routes.index')->with('success', 'Shipping Route deleted successfully.');
    }
}
