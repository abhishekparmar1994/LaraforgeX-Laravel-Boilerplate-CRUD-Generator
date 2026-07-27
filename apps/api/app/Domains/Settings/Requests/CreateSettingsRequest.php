<?php

declare(strict_types=1);

namespace App\Domains\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Define validation rules
        ];
    }
}