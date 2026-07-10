<?php

namespace App\Domains\CRUD\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type_id' => 'required|integer|exists:device_types,id',
            'features' => 'nullable|array',
            'features.*' => 'integer|exists:features,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Ingrese un nombre para el dispositivo.',
            'type_id.required' => 'Seleccione un tipo de producto.',
            'type_id.integer' => 'Seleccione un tipo de producto valido.',
            'type_id.exists' => 'Seleccione un tipo de producto existente.',
            'features.array' => 'Seleccione caracteristicas validas.',
            'features.*.integer' => 'Seleccione caracteristicas validas.',
            'features.*.exists' => 'Seleccione caracteristicas existentes.',
        ];
    }

    /**
     * @return array{name: string, type_id: int|string, features: array<int, int|string>}
     */
    public function validatedDeviceData(): array
    {
        $data = $this->safe()->only(['name', 'type_id', 'features']);
        $data['features'] = $data['features'] ?? [];

        return $data;
    }
}
