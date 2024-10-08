<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryDriverRequest extends FormRequest
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
                    'delivery_company_id' => 'sometimes|exists:delivery_companies,id',
                    'store_id' => 'sometimes|exists:stores,id',
                    'firstname' => 'required|string',
                    'lastname' => 'required|string',
                    'phone' => 'required|string',
                ];
            }
            case 'PUT' :
            {
                return [
                    'delivery_company_id' => 'sometimes|exists:delivery_companies,id',
                    'store_id' => 'sometimes|exists:stores,id',
                    'firstname' => 'required|string',
                    'lastname' => 'required|string',
                    'phone' => 'required|string',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'delivery_company_id' => 'sometimes|exists:delivery_companies,id',
                    'store_id' => 'sometimes|exists:stores,id',
                    'firstname' => 'sometimes|string|nullable',
                    'lastname' => 'sometimes|string|nullable',
                    'phone' => 'sometimes|string|nullable',
                ];
            }
            default :
                return [];
        }
    }
}
