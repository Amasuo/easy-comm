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
                    'store_id' => 'required|exists:stores,id',
                    'firstname' => 'required|string',
                    'lastname' => 'required|string',
                    'phone' => 'required|string',
                    'state' => 'required|string',
                    'city' => 'required|string',
                    'street' => 'required|string',
                ];
            }
            case 'PUT' :
            {
                return [
                    'store_id' => 'required|exists:stores,id',
                    'firstname' => 'required|string',
                    'lastname' => 'required|string',
                    'phone' => 'required|string',
                    'state' => 'required|string',
                    'city' => 'required|string',
                    'street' => 'required|string',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'store_id' => 'sometimes|exists:stores,id',
                    'firstname' => 'sometimes|string',
                    'lastname' => 'sometimes|string',
                    'phone' => 'sometimes|string',
                    'state' => 'sometimes|string',
                    'city' => 'sometimes|string',
                    'street' => 'sometimes|string',
                ];
            }
            default :
                return [];
        }
    }
}
