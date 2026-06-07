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

    public function getAllInstallations(): Collection
    {
        return $this->installationRepository->getAll();
    }


    public function index(Request $request)
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
}
