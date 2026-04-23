<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'family_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
        'family_name.required' => '姓を入力してください。',
        'family_name.string'   => '姓は文字列で入力してください。',
        'family_name.max'      => '姓は50文字以内で入力してください。',

        'last_name.required' => '名を入力してください。',
        'last_name.string'   => '名は文字列で入力してください。',
        'last_name.max'      => '名は50文字以内で入力してください。',

        'email.required' => 'メールアドレスを入力してください。',
        'email.email'    => '正しい形式のメールアドレスを入力してください。',
        'email.max'      => 'メールアドレスは255文字以内で入力してください。',
        'email.unique'   => 'このメールアドレスは既に使用されています。',

        'password.required'  => 'パスワードを入力してください。',
        'password.min'       => 'パスワードは8文字以上で入力してください。',
        'password.confirmed' => 'パスワード確認が一致していません。',
        ];
    }

}
