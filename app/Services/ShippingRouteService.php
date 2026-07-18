<?php

namespace App\Services;

use App\Repositories\ShippingRouteRepositoryInterface;
use Illuminate\Validation\ValidationException;

class ShippingRouteService
{
    protected $shippingRouteRepository;

    public function __construct(ShippingRouteRepositoryInterface $shippingRouteRepository)
    {
        $this->shippingRouteRepository = $shippingRouteRepository;
    }

    public function getPaginatedShippingRoutes(array $filters, int $perPage = 10)
    {
        return $this->shippingRouteRepository->getPaginated($filters, $perPage);
    }

    public function getShippingRouteById(int $id)
    {
        return $this->shippingRouteRepository->findById($id);
    }

    public function createShippingRoute(array $data)
    {
        if ($data['origin_port_id'] == $data['destination_port_id']) {
            throw ValidationException::withMessages([
                'destination_port_id' => 'Origin Port and Destination Port cannot be the same.'
            ]);
        }
        return $this->shippingRouteRepository->create($data);
    }

    public function updateShippingRoute(int $id, array $data)
    {
        if ($data['origin_port_id'] == $data['destination_port_id']) {
            throw ValidationException::withMessages([
                'destination_port_id' => 'Origin Port and Destination Port cannot be the same.'
            ]);
        }
        return $this->shippingRouteRepository->update($id, $data);
    }

    public function deleteShippingRoute(int $id)
    {
        return $this->shippingRouteRepository->delete($id);
    }

    public function getAllPorts()
    {
        return $this->shippingRouteRepository->getAllPorts();
    }
}
