<?php

namespace App\Domains\Clients\Repositories;

use App\Domains\Clients\Models\Installation;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class InstallationRepository
{
    public function createInstallation(array $data): Installation
    {
        return Installation::create($data);
    }

    public function getInstallationById(int $id): Installation
    {
        return Installation::find($id);
    }

    public function getInstallationsByClient(int $id): Collection
    {
        $installations =  DB::table('installations')->where('client_id', $id)->get();
        return $installations;
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

    public function updateInstallation(array $data): int
    {
        $installation = Installation::find($data['id']);
        $installation->update($data);
        return $installation->id;
    }

    public function deleteInstallation(int $id): bool
    {
        return DB::table('installations')->delete($id);
    }
}
