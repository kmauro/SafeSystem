<?php

namespace App\Domains\Clients\Controllers;

use App\Domains\Clients\Requests\NewInstallationDeviceRequest;
use App\Domains\Clients\Requests\NewInstallationRequest;
use App\Domains\Clients\Services\ClientService;
use App\Domains\Clients\Services\InstallationService;
use App\Domains\Configs\Services\ConfigService;
use App\Domains\CRUD\Services\DeviceService;
use App\Domains\Users\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class InstallationController extends Controller
{
    public function __construct(
        protected ConfigService $configService,
        protected ClientService $clientService,
        protected InstallationService $installationService,
        protected UserService $userService,
        protected DeviceService $deviceService,
    ) {}

    public function showDashboard(): InertiaResponse
    {
        return Inertia::render('CRM/Installations/Dashboard', [
            'title' => 'Dashboard',
            'installationStatistics' => $this->installationService->getStateStatistics(),
        ]);
    }

    public function showAddInstallation()
    {
        $categories = $this->configService->getCategories();
        $states = $this->configService->getStates();
        $clients = $this->clientService->getAllClients();
        $users = $this->userService->getAllUsers();

        return Inertia::render('CRM/Installations/AddInstallation', [
            'title' => 'Agregar instalacion',
            'categories' => $categories,
            'states' => $states,
            'clients' => $clients,
            'users' => $users,
        ]);
    }

    public function showIndex(Request $request): InertiaResponse
    {
        $installations = $this->installationService->index($request);
        $states = $this->configService->getStates();

        return Inertia::render('CRM/Installations/index', [
            'title' => 'Instalaciones',
            'installations' => $installations,
            'states' => $states,
            'filters' => [
                'search' => $request->search ?? '',
                'state' => is_array($request->state) ? ($request->state['id'] ?? '') : ($request->state ?? ''),
            ],
        ]);
    }

    public function showInstallation(int $id)
    {
        $installation = $this->installationService->getInstallationById($id);
        $clients = $this->clientService->getAllClients();
        $categories = $this->configService->getCategories();
        $states = $this->configService->getStates();
        $users = $this->userService->getAllUsers();
        $devices = $this->deviceService->getAllDevices();

        return Inertia::render('CRM/Installations/ViewInstallation', [
            'title' => 'Instalacion',
            'installation' => $installation,
            'clients' => $clients,
            'categories' => $categories,
            'states' => $states,
            'users' => $users,
            'devices' => $devices,
        ]);
    }

    public function addInstallation(NewInstallationRequest $request): RedirectResponse
    {
        $validated = $request->validatedInstallationData();
        $installation = $this->installationService->createInstallation($validated);
        if ($installation) {
            return back()->with('success', 'La instalación se agregó correctamente.');
        } else {
            return back()->with('error', 'hubo un problema, intentelo de nuevo.');
        }
    }

    public function updateInstallation(Request $request): RedirectResponse
    {
        $validated = $request->validate([]);
        $installation = $this->installationService->updateInstallation($request->toArray());
        if ($installation) {
            return back()->with('success', 'La instalación se edito correctamente.');
        } else {
            return back()->with('error', 'hubo un problema, intentelo de nuevo.');
        }
    }

    public function addInstallationDevice(NewInstallationDeviceRequest $request, int $id): RedirectResponse
    {
        $this->installationService->addInstallationDevice($id, $request->validatedInstallationDeviceData());

        return back()->with('success', 'El dispositivo se agrego correctamente a la instalacion.');
    }

    public function deleteInstallationDevice(int $installationId, int $installationDeviceId): RedirectResponse
    {
        if ($this->installationService->deleteInstallationDevice($installationId, $installationDeviceId)) {
            return back()->with('success', 'Dispositivo quitado correctamente.');
        }

        return back()->with('error', 'No se pudo quitar el dispositivo. Intentelo de nuevo.');
    }

    public function deleteInstallation(int $id): RedirectResponse
    {
        $response = $this->installationService->deleteInstallation($id);
        if ($response) {
            return redirect()
                ->route('installation.index')
                ->with('success', 'Instalación eliminada correctamente.');
        } else {
            return back()->with('error', 'No se pudo eliminar. Intentelo de nuevo.');
        }
    }
}
