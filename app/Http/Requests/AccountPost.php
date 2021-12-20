<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account' => 'required',
            'user_name' => 'required',
            'birthday' => 'required',
            'email' => 'required'
        ];
    }

    /**
     * 取得已定義驗證規則的錯誤訊息。
     *
     * @return array
     */
    public function messages()
    {
        return [
            'account.required' => '帳號必填',
            'user_name.required'  => '姓名必填',
            'birthday.required'  => '生日必填',
            'email.required'  => '信箱必填',
        ];
    }
}
