<?php

namespace App\Http\Requests\VirtualNumber;

use Illuminate\Foundation\Http\FormRequest;

class ConfigureNumberRequest extends FormRequest
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
            'forward_to' => 'required|string|max:20',
            'forward_sms' => 'boolean',
            'forward_voice' => 'boolean',
            'callback_url' => 'nullable|url'
        ];
    }
}