<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTableRequest extends FormRequest
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
            'table_name'      => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+$/', 'max:64'],
            'engine'          => ['nullable', 'string', 'in:InnoDB,MyISAM,MEMORY'],
            'collation'       => ['nullable', 'string', 'max:64'],
            'columns'         => ['required', 'array', 'min:1'],
            'columns.*.name'  => ['required', 'string', 'regex:/^[a-zA-Z0-9_]+$/', 'max:64'],
            'columns.*.type'  => ['required', 'string'],
            'columns.*.length' => ['nullable', 'string', 'max:64'],
            'columns.*.nullable' => ['nullable', 'boolean'],
            'columns.*.default' => ['nullable'],
            'columns.*.auto_increment' => ['nullable', 'boolean'],
            'columns.*.primary' => ['nullable', 'boolean'],
            'foreign_keys'    => ['nullable', 'array'],
            'indexes'         => ['nullable', 'array'],
        ];
    }
}
