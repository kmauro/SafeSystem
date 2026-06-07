<?php

namespace App\Domains\Clients\Repositories;

use App\Domains\Clients\Models\Installation;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class InstallationRepository
{
    public function createInstallation(array $data): Installation
    {
        return Installation::create($data);
    }

    public function getAll(): Collection
    {
        return Installation::all();
    }

    public function getIndexQuery(): Builder
    {
        return Installation::query()
            ->select('installations.id', 'installations.address', 'installations.description', 'installations.updated_at', 'states.color', 'installations.state_id', 'clients.name')
            ->join('states', 'installations.state_id', '=', 'states.id')
            ->join('clients', 'clients.id', '=', 'installations.client_id')
            ->orderBy('updated_at', 'desc');
    }

    public function addWhere(Builder $query, string $field, string $operator, string $search): Builder
    {
        return $query->where($field, $operator, "%{$search}%");
    }
}
