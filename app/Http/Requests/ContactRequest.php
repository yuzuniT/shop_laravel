<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // 誰でもOK
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
            'email'=>'required|email|max:255',
            'phone_number'=>'nullable',// |regex:/^([0-9]{10,11})$/
            'contact_type'=>'required|in:product,order,return,payment,other',
            'contact_title'=>'required|string|max:100',
            'message'=>'required|string|max:2000'
        ];
    }
    /* カスタムメッセージ
    public function messages(): array
    {
        return [
            'family_name.required' => 'お名前（姓）は必須です。',
            'email.email'          => '正しいメールアドレスを入力してください。',
        ];
    }
    */

}
