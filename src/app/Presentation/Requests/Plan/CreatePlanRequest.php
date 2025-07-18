<?php

declare(strict_types=1);

namespace App\Presentation\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'monthly_price' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'user_limit' => ['required', 'integer', 'min:1'],
            'features' => ['required', 'array'],
            'features.*' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Plan name is required',
            'name.max' => 'Plan name cannot exceed 100 characters',
            'monthly_price.required' => 'Monthly price is required',
            'monthly_price.integer' => 'Monthly price must be an integer',
            'monthly_price.min' => 'Monthly price cannot be negative',
            'currency.required' => 'Currency is required',
            'currency.size' => 'Currency must be 3 characters',
            'user_limit.required' => 'User limit is required',
            'user_limit.integer' => 'User limit must be an integer',
            'user_limit.min' => 'User limit must be at least 1',
            'features.required' => 'Features are required',
            'features.array' => 'Features must be an array',
            'features.*.required' => 'Each feature is required',
            'features.*.string' => 'Each feature must be a string',
            'features.*.max' => 'Each feature cannot exceed 255 characters',
        ];
    }
}