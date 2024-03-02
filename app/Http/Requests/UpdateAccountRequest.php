<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
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
            case 'PUT' :
            {
                // the uniqueness is handled in the controller (because it doesn't accept its own value)
                return [
                    'firstname' => 'required|string',
                    'lastname' => 'required|string',
                    'email' => 'required|email',
                    'phone' => 'required|string',
                    'street' => 'required|string',
                    'state' => 'required|string',
                    'city' => 'required|string',
                    'language_id' => 'sometimes|exists:languages,id',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'firstname' => 'sometimes|string',
                    'lastname' => 'sometimes|string',
                    'email' => 'sometimes||email',
                    'phone' => 'sometimes|string',
                    'street' => 'sometimes|string',
                    'state' => 'sometimes|string',
                    'city' => 'sometimes|string',
                    'language_id' => 'sometimes|exists:languages,id',
                ];
            }
            default :
                return [];
        }
    }
}
