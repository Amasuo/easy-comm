<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
                    'store_id' => 'required|exists:stores,id',
                    'name' => 'required|string',
                    'price' => 'required|numeric',
                ];
            }
            case 'PUT' :
            {
                return [
                    'store_id' => 'required|exists:stores,id',
                    'name' => 'required|string',
                    'price' => 'required|numeric',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'store_id' => 'sometimes|exists:stores,id',
                    'name' => 'sometimes|string',
                    'price' => 'sometimes|numeric',
                ];
            }
            default :
                return [];
        }
    }
}
