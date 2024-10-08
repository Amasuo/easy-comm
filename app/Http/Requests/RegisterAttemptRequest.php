<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAttemptRequest extends FormRequest
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
                    'store' => 'required|string',
                    'store_id' => 'sometimes|exists:stores,id|nullable',
                    'firstname' => 'required|string',
                    'lastname' => 'required|string',
                    'phone' => 'required|string',
                    'email' => 'required|string',
                ];
            }
            default :
                return [];
        }
    }
}
