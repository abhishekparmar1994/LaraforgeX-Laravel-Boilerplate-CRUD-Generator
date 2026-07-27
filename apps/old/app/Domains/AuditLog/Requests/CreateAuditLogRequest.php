<?php

declare(strict_types=1);

namespace App\Domains\AuditLog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAuditLogRequest extends FormRequest
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