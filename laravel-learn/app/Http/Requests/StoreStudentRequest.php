<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
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
        $latestEnrollmentYear = (int) now()->addYear()->format('Y');

        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', Rule::unique('students', 'email')],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'major' => ['required', 'string', 'max:100'],
            'enrollment_year' => ['required', 'integer', 'min:2000', 'max:'.$latestEnrollmentYear],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Add the student name before saving the record.',
            'email.required' => 'An email address is required for each student profile.',
            'email.email' => 'Enter a valid email address for the student.',
            'email.unique' => 'This email is already assigned to another student.',
            'major.required' => 'Choose or enter the student major.',
            'enrollment_year.required' => 'Set the student enrollment year.',
            'enrollment_year.integer' => 'Enrollment year must be a valid year number.',
            'enrollment_year.min' => 'Enrollment year must be 2000 or later.',
            'enrollment_year.max' => 'Enrollment year cannot be later than the next academic year.',
            'status.required' => 'Select whether the student is active or inactive.',
            'status.in' => 'Student status must be active or inactive.',
        ];
    }
}
