<?php

namespace App\Domains\Clients\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Http\RedirectResponse;
use App\Domains\Clients\Requests\NewClientRequest;
use App\Domains\Clients\Requests\ClientRequest;
use App\Domains\Clients\Services\ClientService;

class ClientController extends Controller
{

    public function __construct(protected ClientService $clientService){}

    public function showDashboard(): InertiaResponse
    {
        return Inertia::render('CRM/Clients/Dashboard', [
            'title' => 'Dashboard de Clientes',
        ]);
    }

    public function showAddClient(): InertiaResponse
    {
        return Inertia::render('CRM/Clients/AddClient', [
            'title' => 'Agregar Cliente',
        ]);
    }

    public function showIndex(): InertiaResponse
    {
        $clients = $this->clientService->getAllClients();
        return Inertia::render('CRM/Clients/index', [
            'title' => 'Lista de Clientes',
            'clients' => $clients,
        ]);
    }

    public function showClient(int $id): InertiaResponse
    {
        $client = $this->clientService->getClientById($id);
        $installations = $this->clientService->getClientInstallations($id);
        return Inertia::render('CRM/Clients/ViewClient', [
            'title' => 'Detalle del Cliente',
            'client' => $client,
            'installations' => $installations,
        ]);
    }

    public function addClient(NewClientRequest $request) : RedirectResponse
    {
        $validatedData = $request->validatedProfileData();
        $newClient = $this->clientService->createClient($validatedData);

        if($newClient){
            return redirect()->back()->with('success', 'Cliente agregado exitosamente.');
        }else{
            return redirect()->back()->with('error', 'Hubo un error al agregar el cliente. Por favor, inténtalo de nuevo.');
        }
    }

    public function updateClient(ClientRequest $request) : RedirectResponse
    {
        $validatedData = $request->validatedProfileData();
        $updatedClient = $this->clientService->updateClient($validatedData);

        if($updatedClient){
            return redirect()->back()->with('success', 'Cliente actualizado exitosamente.');
        }else{
            return redirect()->back()->with('error', 'Hubo un error al actualizar el cliente. Por favor, inténtalo de nuevo.');
        }
    }

    public function deleteClient(int $id) : RedirectResponse
    {
        try {
            $this->clientService->deleteClient($id);
            return redirect()->route('clients.index')->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hubo un error al eliminar el cliente. Por favor, inténtalo de nuevo.');
        }
    }
}
