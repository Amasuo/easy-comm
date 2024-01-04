<?php

namespace App\Http\Requests\ProductVariant;

use Illuminate\Foundation\Http\FormRequest;

class ProductVariantRequest extends FormRequest
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
                    'stock' => 'required|numeric',
                    'price' => 'sometimes|numeric|nullable',
                    'image' => 'sometimes',
                    'product_option_values' => 'sometimes',
                ];
            }
            case 'PUT' :
            {
                return [
                    'product_id' => 'required|exists:products,id',
                    'stock' => 'required|numeric',
                    'price' => 'sometimes|numeric|nullable',
                    'image' => 'sometimes',
                    'product_option_values' => 'sometimes',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'product_id' => 'sometimes|exists:products,id',
                    'stock' => 'sometimes|numeric',
                    'price' => 'sometimes|numeric|nullable',
                    'image' => 'sometimes',
                    'product_option_values' => 'sometimes',
                ];
            }
            default :
                return [];
        }
    }
}
