<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BookingRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id',
                function ($attribute, $value, $fail) {

                }],
            'day' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'service_ids' => 'required|exists:services,id',
        ];
    }

    public function messages(): array
    {

        return [
            'user_id.required' => __('booking.user_id_required'),
            'user_id.exists' => __('booking.user_id_exists'),
            'day.required' => __('booking.day_required'),
            'day.after_or_equal' => __('booking.day_after_or_equal'),
            'time.required' => __('booking.time_required'),
            'service_ids.required' => __('booking.service_id_required'),
            'service_ids.exists' => __('booking.service_id_exists'),

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
