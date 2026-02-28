<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadCvRequest extends FormRequest
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
            'ho_ten' => 'required|string|max:255',
            'age' => 'required|integer|min:18|max:100',
            'current_residence' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'level' => 'required|string|max:255',
            'willing_to_travel' => 'nullable|boolean',
            'sex' => 'required|string|in:male,female,other',
            'place_of_birth' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'applied_position' => 'required|string|max:255',
            'file_path' => 'required|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
            'willing_to_work_overtime' => 'nullable|boolean',
            'previous_experiences' => 'nullable|string',
            'personal_experience' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'ho_ten.required' => 'Họ tên là bắt buộc.',
            'age.required' => 'Tuổi là bắt buộc.',
            'age.min' => 'Tuổi phải từ 18 trở lên.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'file_path.required' => 'File CV là bắt buộc.',
            'file_path.mimes' => 'File CV phải là định dạng PDF, DOC hoặc DOCX.',
            'file_path.max' => 'File CV không được vượt quá 10MB.',
        ];
    }
}

