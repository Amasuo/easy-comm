<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductGenderRequest extends FormRequest
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
                    'name' => 'required|string|unique:product_genders',
                ];
            }
            case 'PUT' :
            {
                return [
                    'name' => 'required|string',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'name' => 'sometimes|string',
                ];
            }
            default :
                return [];
        }
    }
}
