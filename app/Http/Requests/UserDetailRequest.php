<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserDetailRequest extends FormRequest
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
            'fullname' => 'required|string',
            'phone_number' => 'required|unique:users|digits:10',
            // 'major_positions.*' => 'nullable|array',
            'services' => 'nullable|array',
            'levels' => 'nullable|array',
            'groups' => 'nullable|array',
            // 'sub_groups.*' => 'nullable|array',
            'positions' => 'nullable|array',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ];
    }
}
