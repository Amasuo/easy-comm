<?php

namespace App\Http\Requests;

use App\Models\ProductVariant;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class OrderBulkUpdateRequest extends FormRequest
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
                return [
                    'orders_ids' => 'required|array',
                    'order_status_id' => 'required|exists:order_statuses,id|nullable',
                ];
            }
            case 'PATCH' :
            {
                return [
                    'orders_ids' => 'required|array',
                    'order_status_id' => 'required|exists:order_statuses,id|nullable',
                ];
            }
            default :
                return [];
        }
    }
}
