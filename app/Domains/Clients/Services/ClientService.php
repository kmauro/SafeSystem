<?php

namespace App\Domains\Clients\Services;

use App\Domains\Clients\Models\Client;
use App\Domains\Clients\Repositories\ClientRepository;
use App\Domains\Clients\Repositories\InstallationRepository;
use Illuminate\Support\Collection;

class ClientService
{
    public function __construct(
        protected ClientRepository $clientRepository,
        protected InstallationRepository $installationRepository,
        )
        {}

    public function createClient(array $data) : Client
    {
            return $this->clientRepository->createClient($data);
    }

    public function getAllClients() : Collection
    {
        return $this->clientRepository->getAllClients();
    }

    public function getClientById(int $id) : Client
    {
        return $this->clientRepository->getClientById($id);
    }

    public function getClientInstallations(int $id): Collection
    {
        return $this->installationRepository->getInstallationsByClient($id);
    }

    public function updateClient(array $data) : Client
    {
        return $this->clientRepository->updateClient($data['id'], $data);
    }

    public function deleteClient(int $id) : void
    {
        $this->clientRepository->deleteClient($id);
    }
}
