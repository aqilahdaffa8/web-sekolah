<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
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
            'nis' => ['required', 'string', 'max:30', Rule::unique('students', 'nis')->ignore($this->route('student'))],
            'name' => ['required', 'string', 'max:255'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'status' => ['required', 'in:aktif,lulus'],
        ];
    }
}
