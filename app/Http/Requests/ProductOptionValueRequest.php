<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductOptionValueRequest extends FormRequest
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
                    'product_option_id' => 'required|exists:product_options,id',
                    'value' => 'required|string',
                ];
            }
            case 'PUT' :
            {
                return [
                    'product_option_id' => 'required|exists:product_options,id',
                    'value' => 'required|string',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'product_option_id' => 'sometimes|exists:product_options,id',
                    'value' => 'sometimes|string',
                ];
            }
            default :
                return [];
        }
    }
}
