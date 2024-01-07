<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
                    'store_id' => 'sometimes|exists:stores,id|nullable',
                    'name' => 'required|string',
                    'price' => 'required|numeric',
                    'purchase_price' => 'required|numeric',
                    'image' => 'sometimes',
                    'product_options' => 'sometimes',
                ];
            }
            case 'PUT' :
            {
                return [
                    'store_id' => 'sometimes|exists:stores,id|nullable',
                    'name' => 'required|string',
                    'price' => 'required|numeric',
                    'purchase_price' => 'required|numeric',
                    'image' => 'sometimes',
                    'product_options' => 'sometimes',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'store_id' => 'sometimes|exists:stores,id|nullable',
                    'name' => 'sometimes|string',
                    'price' => 'sometimes|numeric',
                    'purchase_price' => 'sometimes|numeric',
                    'image' => 'sometimes',
                    'product_options' => 'sometimes',
                ];
            }
            default :
                return [];
        }
    }
}
