<?php

namespace App\Domains\Clients\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:clients,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'cuit' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'El ID del cliente es obligatorio.',
            'id.integer' => 'El ID del cliente debe ser un número entero.',
            'id.exists' => 'El cliente especificado no existe.',
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo válida.',
            'phone.string' => 'El teléfono debe ser una cadena de texto.',
            'phone.max' => 'El teléfono no puede tener más de 20 caracteres.',
        ];
    }

    public function validatedProfileData(): array
    {
        return $this->only(['id', 'name', 'email', 'phone', 'cuit']);
    }
}
