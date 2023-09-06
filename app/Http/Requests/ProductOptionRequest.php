<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductOptionRequest extends FormRequest
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
                    'product_id' => 'required|exists:products,id',
                    'name' => 'required|string',
                ];
            }
            case 'PUT' :
            {
                return [
                    'product_id' => 'required|exists:products,id',
                    'name' => 'required|string',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'product_id' => 'sometimes|exists:products,id',
                    'name' => 'sometimes|string',
                ];
            }
            default :
                return [];
        }
    }
}
