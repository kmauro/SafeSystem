<?php

namespace App\Domains\Clients\Services;

use App\Domains\Clients\Models\Installation;
use App\Domains\Clients\Repositories\InstallationRepository;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class InstallationService
{
    public function __construct(
        protected InstallationRepository $installationRepository,
    ){}

    public function createInstallation(array $data): Installation
    {
        return $this->installationRepository->createInstallation($data);
    }

    public function getInstallationById(int $id): Installation
    {
        return $this->installationRepository->getInstallationById($id);
    }

    public function index(Request $request): Collection
    {
        $search = $request->search ?? '';
        $state = $request->state;
        $query = $this->installationRepository->getIndexQuery();
        $this->installationRepository->addWhere($query, 'installations.address', 'like', $search);

        if ($state) {
            $query->where('state_id', $state['id']);
        }

        return $query
            ->get();
    }

    public function updateInstallation(array $data): int
    {
        return $this->installationRepository->updateInstallation($data);
    }

    public function deleteInstallation(int $id): bool
    {
        return $this->installationRepository->deleteInstallation($id);
    }
}
