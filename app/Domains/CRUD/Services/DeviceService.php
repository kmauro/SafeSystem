<?php

namespace App\Domains\CRUD\Services;

use App\Domains\CRUD\Models\Device;
use App\Domains\CRUD\Repositories\DeviceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DeviceService
{
    public function __construct(
        protected DeviceRepository $deviceRepository,
    ) {}

    public function index(Request $request): Collection
    {
        $search = $request->search ?? '';
        $type = $request->type;
        $typeId = is_array($type) ? ($type['id'] ?? null) : $type;
        $query = $this->deviceRepository->getIndexQuery();

        if ($search !== '') {
            $this->deviceRepository->search($query, $search);
        }

        if ($typeId) {
            $query->where('type_id', (int) $typeId);
        }

        return $query->get();
    }

    public function getAllDeviceTypes(): Collection
    {
        return $this->deviceRepository->getAllDeviceTypes();
    }

    public function getAllFeatures(): Collection
    {
        return $this->deviceRepository->getAllFeatures();
    }

    public function getAllDevices(): Collection
    {
        return $this->deviceRepository->getAllDevices();
    }

    public function getDeviceById(int $id): Device
    {
        return $this->deviceRepository->getDeviceById($id);
    }

    public function createDevice(array $data): Device
    {
        return $this->deviceRepository->createDevice($data);
    }

    public function updateDevice(array $data): Device
    {
        return $this->deviceRepository->updateDevice($data);
    }

    public function deleteDevice(int $id): bool
    {
        return $this->deviceRepository->deleteDevice($id);
    }
}
