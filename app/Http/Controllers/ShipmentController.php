<?php

namespace App\Http\Controllers;

use App\Services\ShipmentService;
use App\Http\Requests\StoreShipmentRequest;
use App\Http\Requests\UpdateShipmentRequest;
use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    protected ShipmentService $shipmentService;

    public function __construct(ShipmentService $shipmentService)
    {
        $this->shipmentService = $shipmentService;
    }

    public function index(Request $request)
    {
        $filters   = $request->only(['search', 'shipment_status', 'origin_country_id', 'destination_country_id', 'sort_by', 'sort_dir']);
        $shipments = $this->shipmentService->getPaginatedShipments($filters, 10);
        $countries = $this->shipmentService->getAllCountries();
        $statuses  = $this->shipmentService->getStatusOptions();

        return view('shipments.index', compact('shipments', 'countries', 'statuses', 'filters'));
    }

    public function create()
    {
        $countries   = $this->shipmentService->getAllCountries();
        $ports       = $this->shipmentService->getAllPorts();
        $statuses    = $this->shipmentService->getStatusOptions();
        $cargoTypes  = $this->shipmentService->getCargoTypeOptions();

        return view('shipments.create', compact('countries', 'ports', 'statuses', 'cargoTypes'));
    }

    public function store(StoreShipmentRequest $request)
    {
        $this->shipmentService->createShipment($request->validated());

        return redirect()->route('shipments.index')
            ->with('success', 'Shipment created successfully.');
    }

    public function show(Shipment $shipment)
    {
        $shipment->load(['originCountry', 'destinationCountry', 'originPort', 'destinationPort']);

        return view('shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $countries  = $this->shipmentService->getAllCountries();
        $ports      = $this->shipmentService->getAllPorts();
        $statuses   = $this->shipmentService->getStatusOptions();
        $cargoTypes = $this->shipmentService->getCargoTypeOptions();

        return view('shipments.edit', compact('shipment', 'countries', 'ports', 'statuses', 'cargoTypes'));
    }

    public function update(UpdateShipmentRequest $request, Shipment $shipment)
    {
        $this->shipmentService->updateShipment($shipment->id, $request->validated());

        return redirect()->route('shipments.index')
            ->with('success', 'Shipment updated successfully.');
    }

    public function destroy(Shipment $shipment)
    {
        $this->shipmentService->deleteShipment($shipment->id);

        return redirect()->route('shipments.index')
            ->with('success', 'Shipment deleted successfully.');
    }
}
