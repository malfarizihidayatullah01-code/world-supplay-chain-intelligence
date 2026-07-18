<?php

namespace App\Services;

use App\Repositories\PortRepositoryInterface;

class PortService
{
    protected $portRepository;

    public function __construct(PortRepositoryInterface $portRepository)
    {
        $this->portRepository = $portRepository;
    }

    public function getPaginatedPorts(array $filters, int $perPage = 10)
    {
        return $this->portRepository->getPaginated($filters, $perPage);
    }

    public function getPortById(int $id)
    {
        return $this->portRepository->findById($id);
    }

    public function createPort(array $data)
    {
        return $this->portRepository->create($data);
    }

    public function updatePort(int $id, array $data)
    {
        return $this->portRepository->update($id, $data);
    }

    public function deletePort(int $id)
    {
        return $this->portRepository->delete($id);
    }

    public function getAllCountries()
    {
        return $this->portRepository->getAllCountries();
    }
}
