<?php

declare(strict_types=1);

namespace App\Domains\Setting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => 'required|string|max:255',
            'value' => 'nullable|string|max:255',
            'group' => 'required|string|max:255',
            'is_encrypted' => 'required|string|max:255'
        ];
    }
}