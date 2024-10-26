<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        switch ($this->method()) {
            case 'POST' :
            {
                return [
                    'name' => 'required|string|unique:delivery_companies',
                    'phone' => 'required|string|unique:delivery_companies',
                    'image' => 'sometimes',
                ];
            }
            case 'PUT' :
            {
                // the uniqueness is handled in the controller (because it doesn't accept its own value)
                return [
                    'name' => 'required|string',
                    'phone' => 'required|string',
                    'image' => 'sometimes',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'name' => 'sometimes|string',
                    'phone' => 'sometimes|string',
                    'image' => 'sometimes',
                ];
            }
            default :
                return [];
        }
    }
}
