<?php

namespace App\Presentation\Requests\Empresa;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request de validación para crear una nueva empresa.
 * Define las reglas de validación y mensajes de error para la creación de empresas.
 */
class CreateEmpresaRequest extends FormRequest
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
                'unique:empresas,email'
            ],
            'plan_id' => [
                'required',
                'uuid',
                'exists:planes,id'
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
            'email.unique' => 'Ya existe una empresa con este email',
            
            'plan_id.required' => 'El plan es obligatorio',
            'plan_id.uuid' => 'El ID del plan debe ser un UUID válido',
            'plan_id.exists' => 'El plan seleccionado no existe'
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
            'plan_id' => 'plan'
        ];
    }
}