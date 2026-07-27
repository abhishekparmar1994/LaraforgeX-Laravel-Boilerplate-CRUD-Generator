<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipmentSupplieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'image' => 'nullable|string|max:255'
        ];
    }
}