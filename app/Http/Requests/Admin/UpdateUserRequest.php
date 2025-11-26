<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $this->route('user')->id],
            'phone' => ['nullable', 'string', 'max:15'],
            'role' => ['required', 'string', 'in:admin,manager,user'],
            'company_id' => ['nullable', 'exists:companies,id', 'required_if:role,manager'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'A name is required',
            'name.max' => 'The name cannot exceed 255 characters',
            'email.required' => 'An email is required',
            'email.email' => 'The email must be a valid email address',
            'email.unique' => 'This email is already in use',
            'phone.max' => 'The phone number cannot exceed 15 characters',
            'role.required' => 'A role is required',
            'role.in' => 'The role must be admin, manager, or user',
            'company_id.exists' => 'The selected company does not exist',
            'company_id.required_if' => 'A company is required for managers',
        ];
    }
}
