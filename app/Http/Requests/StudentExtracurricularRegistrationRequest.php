<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StudentExtracurricularRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'extracurricular_id' => ['required', 'integer', 'exists:extracurriculars,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'extracurricular_id.required' => 'Ekstrakurikuler wajib dipilih.',
            'extracurricular_id.exists' => 'Ekstrakurikuler tidak ditemukan.',
        ];
    }
}
