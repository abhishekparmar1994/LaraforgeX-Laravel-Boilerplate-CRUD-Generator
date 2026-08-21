<?php

declare(strict_types=1);

namespace App\Domains\Setting\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => 'sometimes|string|max:255',
            'value' => 'nullable|string|max:255',
            'group' => 'sometimes|string|max:255',
            'is_encrypted' => 'sometimes|string|max:255'
        ];
    }
}