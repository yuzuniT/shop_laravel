<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryFormRequest extends FormRequest
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
            'family_name'=>'required|string|max:50',
            'last_name'=>'required|string|max:50',
            'postal_code'=>'required|string|max:8',
            'address'=>'required|string|max:255',
            'email'=>'required|email|max:255',
            'phone_number'=>'nullable',// |regex:/^([0-9]{10,11})$/
            'payment_method'=>'required|in:credit_card,convenient_store,cash_on_delivery,bank_transfer',
        ];
    }
}
