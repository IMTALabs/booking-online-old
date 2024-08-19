<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class StaffRequest extends FormRequest
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
            'current_password' => 'required|string',
            'name' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|max:15',
        ];

    }

    public function messages(): array
    {
        return [
            'current_password.required' => __('staff.current_password_required'),
            'new_password.min' => __('staff_new_password_min'),
            'new_password.max' => __('staff_new_password_max'),
            'name.required' => __('staff_name_required'),
            'name.string' => __('staff.name_string'),
            'image.mimes' => __('staff.image_mimes'),
            'phone.string' => __('staff.phone_string'),
            'address.string' => __('staff.address_string'),
        ];

    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(
            [
                'error' => $errors,
                'status_code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ],
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        ));
    }
}
