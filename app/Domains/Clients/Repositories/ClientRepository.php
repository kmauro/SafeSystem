<?php

namespace App\Domains\Clients\Repositories;

use App\Domains\Clients\Models\Client;
use Illuminate\Support\Collection;

class ClientRepository
{


    public function createClient(array $data): Client
    {
        return Client::create($data);
    }

    public function getAllClients(): Collection
    {
        return Client::all();
    }

    public function getClientById(int $id): Client
    {
        return Client::findOrFail($id);
    }

    public function updateClient(int $id, array $data): Client
    {
        $client = Client::findOrFail($id);
        $client->update($data);
        return $client;
    }

    public function deleteClient(int $id): void
    {
        $client = Client::findOrFail($id);
        $client->delete();
    }

}
