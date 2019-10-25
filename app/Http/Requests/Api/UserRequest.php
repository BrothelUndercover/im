<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'username' => 'required|unique:users|max:12',
            'password' => 'required|min:6'
        ];
    }

    public function attribute()
    {
        return [
            'username' => '账号',
            'password' => '密码'
        ];
    }

    public function messages()
    {
        return [
            'username.required' => '账号必填',
            'username.unique:users' => '账号已存在',
            'username.max:50'   => '账号字符小于12'，
            'password.required' => '密码必填',
            'password.min:6' => '密码6位数'
        ];
    }
}

