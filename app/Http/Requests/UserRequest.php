<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
                    'role_id' => 'required',
                    'store_ids'=> 'sometimes|array',
                    'firstname' => 'required|string',
                    'lastname' => 'required|string',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:8',
                    'phone' => 'required|min:8',
                    'is_active' => 'required|boolean',
                ];
            }
            case 'PUT' :
            {
                // the uniqueness is handled in the controller (because it doesn't accept its own value)
                return [
                    'role_id' => 'sometimes',
                    'store_ids'=> 'sometimes|array',
                    'firstname' => 'required|string',
                    'lastname' => 'required|string',
                    'email' => 'required|email',
                    'password' => 'sometimes|min:8',
                    'phone' => 'required|min:8',
                    'is_active' => 'required|boolean',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'role_id' => 'sometimes',
                    'store_ids'=> 'sometimes|array',
                    'firstname' => 'sometimes|string',
                    'lastname' => 'sometimes|string',
                    'email' => 'sometimes||email',
                    'password' => 'sometimes|min:8',
                    'phone' => 'sometimes|min:8',
                    'is_active' => 'sometimes|boolean',
                ];
            }
            default :
                return [];
        }
    }
}
