<?php

namespace App\Repositories;

interface RouteRiskAnalysisRepositoryInterface
{
    public function getPaginated(array $filters, int $perPage = 10);

    public function findById(int $id);

    public function findByShipmentId(int $shipmentId);

    public function create(array $data);

    public function update(int $id, array $data);

    public function updateOrCreateByShipment(int $shipmentId, array $data);

    public function delete(int $id);

    public function getAll();
}
