<?php

namespace App\Domains\Configs\Controllers;

use App\Domains\Configs\Services\ConfigService;
use App\http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ConfigController extends Controller
{
    public function __construct(
        protected ConfigService $configService,
    ) {}

    public function showSettings(): InertiaResponse
    {
        return Inertia::render('User/Settings', [
            'title' => 'Configuración',
        ]);
    }

    public function addCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Ingrese una categoria.',
        ]);
        $category = $this->configService->createCategory($validated);
        if ($category) {
            return back()->with('success', 'La categoría se creó correctamente.');
        } else {
            return back()->with('error', 'No se pudo crear la categoria, intentelo de nuevo.');
        }
    }

    public function addDeviceType(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
        ], [
            'type.required' => 'Ingrese un tipo válido.',
        ]);
        $type = $this->configService->createDeviceType($validated);
        if ($type) {
            return back()->with('success', 'El tipo de dispositivo se creó correctamente.');
        } else {
            return back()->with('error', 'No se pudo crear el tipo de dispositivo, intentelo de nuevo.');
        }
    }

    public function addFeature(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'feature' => 'required|string|max:255',
        ], [
            'feature.required' => 'Ingrese una caracteristica válida.',
        ]);
        $feature = $this->configService->createFeature($validated);
        if ($feature) {
            return back()->with('success', 'La caracteristica se creó correctamente.');
        } else {
            return back()->with('error', 'No se pudo crear la caracteristica, intentelo de nuevo.');
        }
    }

    public function addState(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:255',
        ], [
            'name.required' => 'Ingrese un estado.',
            'color.required' => 'Seleccione un color.',
        ]);
        $state = $this->configService->createState($validated);
        if ($state) {
            return back()->with('success', 'El estado se creó correctamente.');
        } else {
            return back()->with('error', 'No se pudo crear el estado, intentelo de nuevo');
        }
    }
}
