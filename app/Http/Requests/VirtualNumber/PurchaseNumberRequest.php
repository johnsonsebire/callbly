<?php

namespace App\Http\Requests\VirtualNumber;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseNumberRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'number' => 'required|string|max:20',
            'forward_to' => 'nullable|string|max:20',
            'forward_sms' => 'boolean',
            'forward_voice' => 'boolean',
            'callback_url' => 'nullable|url',
            'country_code' => 'required|string|size:2',
            'number_type' => 'required|string|in:local,toll-free,premium'
        ];
    }
}