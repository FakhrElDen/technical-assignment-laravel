<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tenant_key' => 'required|string',
            'device_uid' => 'required|string',
            'event_uid' => 'required|string',
            'type' => 'required|string',
            'occurred_at' => 'required|date',
            'payload' => 'required|array',
        ];
    }

}
