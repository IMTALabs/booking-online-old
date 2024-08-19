<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class OpeningHourRequest extends FormRequest
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
            'store_id' => 'exists:store_information,id',
            'opening_hours' => 'required|array',
            'opening_hours.*.day' => 'required|date|after_or_equal:today',
            'opening_hours.*.opening_time' => [
                'required',
                'regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',
                'date_format:H:i:s',
            ],
            'opening_hours.*.closing_time' => [
                'required',
                'regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',
                'date_format:H:i:s',
                'after:opening_hours.*.opening_time',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'store_id.exists' => __('openingHours.exists'),
            'opening_hours.*.day.required' => __('openingHours.opening_hours_day_required'),
            'opening_hours.*.day.after_or_equal' => __('openingHours.opening_hours_day_after_or_equal'),
            'opening_hours.*.opening_time.required' => __('openingHours.opening_hours_opening_time_required'),
            'opening_hours.*.opening_time.date_format' => __('openingHours.opening_hours_opening_time_date_format'),
            'opening_hours.*.opening_time.regex' => __('openingHours.opening_hours_opening_time_regex'),
            'opening_hours.*.closing_time.required' => __('openingHours.closing_time_required'),
            'opening_hours.*.closing_time.date_format' => __('openingHours.closing_time_date_format'),
            'opening_hours.*.closing_time.regex' => __('openingHours.closing_time_regex'),
            'opening_hours.*.closing_time.after' => __('openingHours.opening_hours_closing_time_after'),
        ];
    }

    public function attributes(): array
    {
        return [
            'opening_hours.*.day' => 'day',
            'opening_hours.*.opening_time' => 'start_time',
            'opening_hours.*.closing_time' => 'closing_time',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $formattedErrors = $this->formatErrors($errors);
        throw new HttpResponseException(response()->json(
            [
                'error' => $formattedErrors,
                'status_code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            ],
            JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        ));
    }

    protected function formatErrors(array $errors)
    {
        $formattedErrors = [];
        foreach ($errors as $key => $messages) {
            $simpleKey = $this->simplifyAttribute($key);
            foreach ($messages as $message) {
                $formattedErrors[$simpleKey][] = $message;
            }
        }

        return $formattedErrors;
    }

    protected function simplifyAttribute($attribute)
    {
        $attributeMap = [
            'opening_hours.*.day' => 'day',
            'opening_hours.*.opening_time' => 'start_time',
            'opening_hours.*.closing_time' => 'closing_time',
        ];
        $attributeWithoutIndex = preg_replace('/\.\d+\./', '.*.', $attribute);
        return $attributeMap[$attributeWithoutIndex] ?? $attribute;
    }
}
