<?php

namespace App\Domains\Clients\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewInstallationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address' => 'required|string|max:255',
            'description' => 'required|string|max:2550',
            'annotation' => 'nullable|string|max:255',
            'client_id' => 'required|integer|exists:clients,id',
            'category_id' => 'required|integer|exists:categories,id',
            'state_id' => 'required|integer|exists:states,id',
            'assigned_user_id' => 'required|integer|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'address.required' => 'Ingrese una dirección',
            'description.required' => 'Ingrese una descripción',
            'client_id.required' => 'Seleccione un cliente.',
            'client_id.integer' => 'Seleccione un cliente valido.',
            'client_id.exists' => 'Seleccione un cliente existente.',
            'category_id.required' => 'Seleccione una categoria.',
            'category_id.integer' => 'Seleccione una categoria valida.',
            'category_id.exists' => 'Seleccione una categoria existente.',
            'state_id.required' => 'Seleccione un estado.',
            'state_id.integer' => 'Seleccione un estado valido.',
            'state_id.exists' => 'Seleccione un estado existente.',
            'assigned_user_id.required' => 'Asigne un responsable.',
            'assigned_user_id.integer' => 'Seleccione un responsable válido.',
            'assigned_user_id.exist' => 'Seleccione un responsable existente.',
        ];
    }

    public function validatedInstallationData(): array
    {
        return $this->only(['address', 'description', 'annotation', 'client_id', 'category_id', 'state_id', 'assigned_user_id']);
    }
}
