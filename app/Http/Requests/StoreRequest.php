<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
                    'name' => 'required|string|unique:stores',
                    'patent_number' => 'sometimes|string|unique:stores|nullable',
                ];
            }
            case 'PUT' :
            {
                return [
                    'name' => 'required|string',
                    'patent_number' => 'sometimes|string|nullable',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'name' => 'sometimes|string',
                    'patent_number' => 'sometimes|string|nullable',
                ];
            }
            default :
                return [];
        }
    }
}
