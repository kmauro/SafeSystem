<?php

namespace App\Domains\Clients\Services;

use App\Domains\Clients\Models\Installation;
use App\Domains\Clients\Repositories\InstallationRepository;
use App\Domains\Configs\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InstallationService
{
    public function __construct(
        protected InstallationRepository $installationRepository,
    ) {}

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
        $stateId = is_array($state) ? ($state['id'] ?? null) : $state;
        $query = $this->installationRepository->getIndexQuery();
        $this->installationRepository->addWhere($query, 'installations.address', 'like', $search);

        if ($stateId) {
            $query->where('state_id', (int) $stateId);
        }

        return $query
            ->get();
    }

    /**
     * @return array{total: int, states: list<array{id: int, name: string, color: string, count: int, percentage: float}>}
     */
    public function getStateStatistics(): array
    {
        $states = $this->installationRepository->getStateStatistics();
        $totalInstallations = (int) $states->sum('installations_count');

        return [
            'total' => $totalInstallations,
            'states' => $states
                ->map(fn (State $state): array => [
                    'id' => $state->id,
                    'name' => $state->name,
                    'color' => $state->color,
                    'count' => (int) $state->installations_count,
                    'percentage' => $totalInstallations > 0
                        ? round(((int) $state->installations_count / $totalInstallations) * 100, 1)
                        : 0.0,
                ])
                ->values()
                ->all(),
        ];
    }

    public function updateInstallation(array $data): int
    {
        return $this->installationRepository->updateInstallation($data);
    }

    public function addInstallationDevice(int $installationId, array $data): void
    {
        $this->installationRepository->addInstallationDevice($installationId, $data);
    }

    public function deleteInstallationDevice(int $installationId, int $installationDeviceId): bool
    {
        return $this->installationRepository->deleteInstallationDevice($installationId, $installationDeviceId);
    }

    public function deleteInstallation(int $id): bool
    {
        return $this->installationRepository->deleteInstallation($id);
    }
}
