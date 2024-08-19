<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ScheduleRequest extends FormRequest
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
            'schedules' => 'required|array',
            'schedules.*.day' => 'required|date|after_or_equal:today',

            'schedules.*.start_time' => [
                'required',
                'regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',
                'date_format:H:i:s',
            ],
            'schedules.*.end_time' => [
                'required',
                'regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',
                'date_format:H:i:s',
                'after:schedules.*.start_time',
            ],
        ];

    }

    public function messages(): array
    {

        return [
            'schedules.*.day.after_or_equal' => __('schedule.schedules_day_after_or_equal'),
            'schedules.*.day' => __('schedule.schedules_day'),
            'schedules.*.day.*.start_time.required' => __('schedule.schedules_day_start_time_required'),
            'schedules.*.day.*.start_time.date_format' => __('schedule.schedules_day_start_time_date_format'),
            'schedules.*.end_time' => __('schedule.schedules_end_time'),
            'schedules.*.end_time.date_format' => __('schedule.schedules_end_time_date_format'),
            'schedules.*.end_time.after' => __('schedule.schedules_end_time_after'),

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
