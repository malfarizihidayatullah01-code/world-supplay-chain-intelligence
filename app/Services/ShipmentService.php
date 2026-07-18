<?php

namespace App\Services;

use App\Repositories\ShipmentRepositoryInterface;
use App\Repositories\CountryRepositoryInterface;
use App\Repositories\PortRepositoryInterface;

class ShipmentService
{
    protected ShipmentRepositoryInterface  $shipmentRepository;
    protected CountryRepositoryInterface   $countryRepository;
    protected PortRepositoryInterface      $portRepository;

    public function __construct(
        ShipmentRepositoryInterface $shipmentRepository,
        CountryRepositoryInterface  $countryRepository,
        PortRepositoryInterface     $portRepository
    ) {
        $this->shipmentRepository = $shipmentRepository;
        $this->countryRepository  = $countryRepository;
        $this->portRepository     = $portRepository;
    }

    // ── Read ──────────────────────────────────────────────────────

    public function getPaginatedShipments(array $filters, int $perPage = 10)
    {
        return $this->shipmentRepository->getPaginated($filters, $perPage);
    }

    public function findShipment(int $id)
    {
        return $this->shipmentRepository->findById($id);
    }

    // ── Write ─────────────────────────────────────────────────────

    public function createShipment(array $data)
    {
        // Auto-generate shipment code
        $data['shipment_code'] = $this->shipmentRepository->nextCode();

        return $this->shipmentRepository->create($data);
    }

    public function updateShipment(int $id, array $data)
    {
        return $this->shipmentRepository->update($id, $data);
    }

    public function deleteShipment(int $id): bool
    {
        return $this->shipmentRepository->delete($id);
    }

    // ── Supporting data for views ─────────────────────────────────

    public function getAllCountries()
    {
        return $this->countryRepository->getAll();
    }

    public function getAllPorts()
    {
        return $this->portRepository->getAll();
    }

    // ── Constants ────────────────────────────────────────────────

    public function getStatusOptions(): array
    {
        return ['Planning', 'In Transit', 'Delivered', 'Delayed', 'Cancelled'];
    }

    public function getCargoTypeOptions(): array
    {
        return [
            'General Cargo',
            'Bulk Cargo',
            'Container',
            'Liquid Bulk',
            'Break Bulk',
            'Ro-Ro',
            'Refrigerated',
            'Hazardous',
            'Project Cargo',
            'Other',
        ];
    }
}
