<?php

namespace App\Domains\Clients\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewInstallationDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => 'required|integer|exists:devices,id',
            'code' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'device_id.required' => 'Seleccione un dispositivo.',
            'device_id.integer' => 'Seleccione un dispositivo valido.',
            'device_id.exists' => 'Seleccione un dispositivo existente.',
            'code.required' => 'Ingrese el codigo del dispositivo.',
            'code.max' => 'El codigo no puede tener mas de 255 caracteres.',
        ];
    }

    /**
     * @return array{device_id: int|string, code: string}
     */
    public function validatedInstallationDeviceData(): array
    {
        return $this->safe()->only(['device_id', 'code']);
    }
}
