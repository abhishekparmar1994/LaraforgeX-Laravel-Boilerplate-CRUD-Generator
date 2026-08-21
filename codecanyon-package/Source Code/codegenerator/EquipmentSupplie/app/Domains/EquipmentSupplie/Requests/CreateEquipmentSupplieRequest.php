<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEquipmentSupplieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'image' => 'nullable|string|max:255'
        ];
    }
}