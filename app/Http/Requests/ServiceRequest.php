<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class
ServiceRequest extends FormRequest
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
            'name' => 'required|string|unique:services',
            'categorie_id' => 'required|integer|exists:categories,id',
            'describe' => 'required|string|max:360',
            'time' => 'required|integer',
            'price' => ['required', 'regex:/^\d{1,9}(,\d{3})*(\.\d{1,2})?$/'],
        ];

    }

    public function messages(): array
    {

        return [
            'time.required' => __('service.time_required'),
            'name.unique' => __('service.name_unique'),
            'name.required' => __('service.name_required'),
            'categorie_id.required' => __('service.categorie_id_required'),
            'categorie_id.integer' => __('service.categorie_id_integer'),
            'categorie_id.exists' => __('service.categorie_id_exists'),
            'describe.required' => __('service.describe_required'),
            'describe.max' => __('service.describe_max'),
            'price.required' => __('service.price_required'),
            'price.regex' => __('service.price_regex'),

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
