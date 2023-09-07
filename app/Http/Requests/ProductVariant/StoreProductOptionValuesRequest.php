<?php

namespace App\Http\Requests\ProductVariant;

use App\Models\ProductOptionValue;
use Closure;
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
            'ids.*' => [
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!ProductOptionValue::find($value)) {
                        $fail("{$value} is invalid.");
                    }
            },]
        ];
    }
}
