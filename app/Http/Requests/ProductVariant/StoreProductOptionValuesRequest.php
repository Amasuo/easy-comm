<?php

namespace App\Http\Requests\ProductVariant;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductOptionValuesRequest extends FormRequest
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
        return [
            'ids' => 'required',
            'ids.*' => 'exists:product_option_values,id'
        ];
    }
}
