<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class userRequest extends FormRequest
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
            'email' => 'required|string|email|unique:users',
            'name' => 'nullable|string',
            'password' => 'required|string|min:6|max:15',
            'role' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,png,jpeg',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'store_id' => 'nullable|integer|exists:store_information,id',
        ];
    }

    public function messages(): array
    {

        return [
            'email.unique' => __('user.email_unique'),
            'email.required' => __('user.email_required'),
            'email.email' => __('user.email_email'),
            'password.required' => __('user.password_required'),
            'password.confirmed' => __('user.password_confirmed'),
            'password.min' => __('auth.password_min'),
            'password.max' => __('auth.password_max'),
            'name.required' => __('user.name_required'),
            'name.string' => __('user.name_string'),
            'image.mimes' => __('user.image_mimes'),
            'phone.string' => __('user.phone_string'),
            'address.string' => __('user.address_string'),
            'store_id.exists' => __('store.not_found'),

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
