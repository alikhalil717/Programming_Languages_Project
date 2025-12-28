<?php

namespace App\Http\Requests;

use App\Models\Apartment;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class CreateRentalRequest extends FormRequest
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
        $today = Carbon::today()->format('Y-m-d');
        return [
            'start_date' => ['required', 'date', 'after_or_equal:' . $today],
            'end_date' => ['required', 'date', 'after:start_date'],
            'special_requests' => 'nullable|string|max:500',
            'payment_method' => 'nullable|string|in:credit_card,paypal,wallet',
        ];
    }
    public function messages(): array
    {
        return [
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'start_date.after_or_equal' => 'Start date must be today or a future date.',
            'end_date.required' => 'End date is required.',
            'end_date.date' => 'End date must be a valid date.',
            'end_date.after' => 'End date must be after the start date.',
            'special_requests.string' => 'Special requests must be a string.',
            'special_requests.max' => 'Special requests may not be greater than 500 characters.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Payment method must be one of the following: credit_card, paypal, wallet.',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}