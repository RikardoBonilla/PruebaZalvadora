<?php

namespace App\Presentation\Requests\Empresa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request de validación para actualizar una empresa existente.
 * Define las reglas de validación y mensajes de error para la actualización de empresas.
 */
class UpdateEmpresaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta request.
     */
    public function authorize(): bool
    {
        return true; // La autorización se maneja en las policies
    }

    /**
     * Obtiene las reglas de validación que se aplican a la request.
     */
    public function rules(): array
    {
        $empresaId = $this->route('empresa'); // Obtiene el ID de la empresa de la ruta
        
        return [
            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-ZÀ-ÿ0-9\s\-\.\,\&\(\)]+$/u'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('empresas', 'email')->ignore($empresaId)
            ],
            'plan_id' => [
                'sometimes',
                'uuid',
                'exists:planes,id'
            ],
            'motivo_cambio' => [
                'required_with:plan_id',
                'string',
                'max:500'
            ]
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para las reglas de validación.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la empresa es obligatorio',
            'nombre.string' => 'El nombre de la empresa debe ser texto',
            'nombre.min' => 'El nombre de la empresa debe tener al menos 2 caracteres',
            'nombre.max' => 'El nombre de la empresa no puede tener más de 255 caracteres',
            'nombre.regex' => 'El nombre de la empresa contiene caracteres no válidos',
            
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe tener un formato válido',
            'email.max' => 'El email no puede tener más de 255 caracteres',
            'email.unique' => 'Ya existe otra empresa con este email',
            
            'plan_id.uuid' => 'El ID del plan debe ser un UUID válido',
            'plan_id.exists' => 'El plan seleccionado no existe',
            
            'motivo_cambio.required_with' => 'El motivo del cambio es obligatorio cuando se cambia el plan',
            'motivo_cambio.string' => 'El motivo del cambio debe ser texto',
            'motivo_cambio.max' => 'El motivo del cambio no puede tener más de 500 caracteres'
        ];
    }

    /**
     * Obtiene los nombres de los atributos personalizados.
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre de la empresa',
            'email' => 'email',
            'plan_id' => 'plan',
            'motivo_cambio' => 'motivo del cambio'
        ];
    }
}