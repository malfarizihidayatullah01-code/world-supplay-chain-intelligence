<?php

namespace App\Services;

use App\Repositories\CountryRepositoryInterface;

class CountryService
{
    protected $countryRepository;

    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function getPaginatedCountries(array $filters, int $perPage = 10)
    {
        return $this->countryRepository->getPaginated($filters, $perPage);
    }

    public function getCountryById(int $id)
    {
        return $this->countryRepository->findById($id);
    }

    public function createCountry(array $data)
    {
        // Business logic or data transformation can be added here
        return $this->countryRepository->create($data);
    }

    public function updateCountry(int $id, array $data)
    {
        return $this->countryRepository->update($id, $data);
    }

    public function deleteCountry(int $id)
    {
        return $this->countryRepository->delete($id);
    }
}
