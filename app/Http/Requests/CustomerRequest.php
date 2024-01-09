<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
                    'firstname' => 'required|string',
                    'lastname' => 'required|string',
                    'phone' => 'required|string',
                    'state' => 'required|string',
                    'city' => 'required|string',
                    'street' => 'sometimes|string|nullable',
                ];
            }
            case 'PUT' :
            {
                return [
                    'store_id' => 'sometimes|exists:stores,id|nullable',
                    'firstname' => 'required|string',
                    'lastname' => 'required|string',
                    'phone' => 'required|string',
                    'state' => 'required|string',
                    'city' => 'required|string',
                    'street' => 'sometimes|string|nullable',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'store_id' => 'sometimes|exists:stores,id|nullable',
                    'firstname' => 'sometimes|string|nullable',
                    'lastname' => 'sometimes|string|nullable',
                    'phone' => 'sometimes|string|nullable',
                    'state' => 'sometimes|string|nullable',
                    'city' => 'sometimes|string|nullable',
                    'street' => 'sometimes|string|nullable',
                ];
            }
            default :
                return [];
        }
    }
}
