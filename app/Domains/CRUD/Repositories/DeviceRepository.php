<?php

namespace App\Domains\CRUD\Repositories;

use App\Domains\CRUD\Models\Device;
use App\Domains\CRUD\Models\DeviceType;
use App\Domains\CRUD\Models\Feature;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DeviceRepository
{
    public function getIndexQuery(): Builder
    {
        return Device::query()
            ->with(['type:id,type', 'features:id,feature'])
            ->orderBy('devices.id', 'desc');
    }

    public function search(Builder $query, string $search): Builder
    {
        return $query->where(function (Builder $query) use ($search): void {
            $query
                ->where('devices.name', 'like', "%{$search}%")
                ->orWhereHas('type', function (Builder $query) use ($search): void {
                    $query->where('type', 'like', "%{$search}%");
                })
                ->orWhereHas('features', function (Builder $query) use ($search): void {
                    $query->where('feature', 'like', "%{$search}%");
                });
        });
    }

    public function getAllDeviceTypes(): Collection
    {
        return DeviceType::query()
            ->orderBy('type')
            ->get();
    }

    public function getAllFeatures(): Collection
    {
        return Feature::query()
            ->orderBy('feature')
            ->get();
    }

    public function getAllDevices(): Collection
    {
        return Device::query()
            ->with(['type:id,type'])
            ->orderBy('name')
            ->get();
    }

    public function getDeviceById(int $id): Device
    {
        return Device::query()
            ->with(['type:id,type', 'features:id,feature'])
            ->findOrFail($id);
    }

    public function createDevice(array $data): Device
    {
        $featureIds = $data['features'] ?? [];
        unset($data['features']);

        $device = Device::create($data);
        $device->features()->sync($featureIds);

        return $device->load(['type:id,type', 'features:id,feature']);
    }

    public function updateDevice(array $data): Device
    {
        $deviceId = $data['id'];
        $featureIds = $data['features'] ?? [];
        unset($data['features'], $data['id']);

        $device = Device::findOrFail($deviceId);
        $device->update($data);
        $device->features()->sync($featureIds);

        return $device->load(['type:id,type', 'features:id,feature']);
    }

    public function deleteDevice(int $id): bool
    {
        return Device::findOrFail($id)->delete();
    }
}
