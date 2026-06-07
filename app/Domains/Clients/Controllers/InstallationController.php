<?php

namespace App\Domains\Clients\Controllers;

use App\Domains\Clients\Requests\NewInstallationRequest;
use App\Domains\Clients\Services\ClientService;
use App\Domains\Clients\Services\InstallationService;
use App\Domains\Configs\Services\ConfigService;
use App\Domains\Users\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class InstallationController extends Controller
{
    public function __construct(
        protected ConfigService $configService,
        protected ClientService $clientService,
        protected InstallationService $installationService,
        protected UserService $userService,
    ){}

    public function showDashboard(): InertiaResponse
    {
        return Inertia::render('CRM/Clients/Dashboard', [
            'title' => 'Dashboard de instalaciones',
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
                'state' => $request->state ?? '',
            ],
        ]);
    }

    public function addInstallation(NewInstallationRequest $request): RedirectResponse
    {
        $validated = $request->validatedInstallationData();
        $installation = $this->installationService->createInstallation($validated);
        if($installation){
            return back()->with('success', 'La instalación se agregó correctamente.');
        }else{
            return back()->with('error', 'hubo un problema, intentelo de nuevo.');
        }
    }

    public function viewInstallation(int $id)
    {
        $installation = $id;
        return Inertia::render('CRM/Installations/ViewInstallation', [
            'title' => 'Instalacion',
            'installation' => $installation
        ]);
    }
}
