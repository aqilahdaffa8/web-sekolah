<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nis' => ['required', 'string', 'max:30', 'unique:students,nis'],
            'name' => ['required', 'string', 'max:255'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'status' => ['required', 'in:aktif,lulus'],
            'create_account' => ['nullable', 'boolean'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'required_if:create_account,true', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8', 'required_if:create_account,true'],
        ];
    }
}
