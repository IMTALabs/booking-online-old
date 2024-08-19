<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class StoreInformationRequest extends FormRequest
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
            'name' => 'required|string|unique:store_information',
            'image' => 'required|image|mimes:jpg,png,jpeg',
            'address' => 'required|string',
            'phone' => 'required|string',
            'location' => 'nullable|string',
        ];

    }

    public function messages(): array
    {

        return [
            'name.unique' => __('store.name_unique'),
            'name.required' => __('store.name_required'),
            'name.string' => __('store.name_string'),
            'address.string' => __('store.address_string'),
            'address.required' => __('store.address_required'),
            'phone.string' => __('store.phone_string'),
            'phone.required' => __('store.phone_required'),

            'image.mimes' => __('store.image_mimes'),
            'image.required' => __('store.image_required'),
            'location.string' => __('store.location_string'),

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
