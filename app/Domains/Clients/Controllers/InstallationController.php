<?php

namespace App\Domains\Clients\Controllers;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class InstallationController extends Controller
{

    public function showDashboard(): InertiaResponse
    {
        return Inertia::render('CRM/Clients/Dashboard', [
            'title' => 'Dashboard de instalaciones',
        ]);
    }

    public function showAddInstallation()
    {
        return Inertia::render('CRM/Installations/AddInstallation', [
            'title' => 'Agregar instalacion',
        ]);
    }

    public function showIndex()
    {
        return Inertia::render('CRM/Installations/index', [
            'title' => 'Instalaciones',
        ]);
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
