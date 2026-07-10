<?php

namespace App\Domains\CRUD\Controllers;

use App\Domains\CRUD\Requests\DeviceRequest;
use App\Domains\CRUD\Requests\NewDeviceRequest;
use App\Domains\CRUD\Services\DeviceService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class DeviceController extends Controller
{
    public function __construct(
        protected DeviceService $deviceService,
    ) {}

    public function showIndex(Request $request): InertiaResponse
    {
        $devices = $this->deviceService->index($request);
        $deviceTypes = $this->deviceService->getAllDeviceTypes();

        return Inertia::render('CRUD/index', [
            'title' => 'Dispositivos',
            'devices' => $devices,
            'deviceTypes' => $deviceTypes,
            'filters' => [
                'search' => $request->search ?? '',
                'type' => is_array($request->type) ? ($request->type['id'] ?? '') : ($request->type ?? ''),
            ],
        ]);
    }

    public function showAddDevice(): InertiaResponse
    {
        return Inertia::render('CRUD/AddDevice', [
            'title' => 'Agregar dispositivo',
            'deviceTypes' => $this->deviceService->getAllDeviceTypes(),
            'features' => $this->deviceService->getAllFeatures(),
        ]);
    }

    public function showDevice(int $id): InertiaResponse
    {
        return Inertia::render('CRUD/ViewDevice', [
            'title' => 'Dispositivo',
            'device' => $this->deviceService->getDeviceById($id),
            'deviceTypes' => $this->deviceService->getAllDeviceTypes(),
            'features' => $this->deviceService->getAllFeatures(),
        ]);
    }

    public function addDevice(NewDeviceRequest $request): RedirectResponse
    {
        $device = $this->deviceService->createDevice($request->validatedDeviceData());

        if ($device) {
            return back()->with('success', 'El dispositivo se agrego correctamente.');
        }

        return back()->with('error', 'Hubo un problema, intentelo de nuevo.');
    }

    public function updateDevice(DeviceRequest $request): RedirectResponse
    {
        $device = $this->deviceService->updateDevice($request->validatedDeviceData());

        if ($device) {
            return back()->with('success', 'El dispositivo se edito correctamente.');
        }

        return back()->with('error', 'Hubo un problema, intentelo de nuevo.');
    }

    public function deleteDevice(int $id): RedirectResponse
    {
        if ($this->deviceService->deleteDevice($id)) {
            return redirect()
                ->route('devices.index')
                ->with('success', 'Dispositivo eliminado correctamente.');
        }

        return back()->with('error', 'No se pudo eliminar. Intentelo de nuevo.');
    }
}
