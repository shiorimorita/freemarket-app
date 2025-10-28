<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => ['required','max:20'],
            'image_path' => ['required','mimes:jpeg,png'],
            'post_code' => ['required','regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザー名を入力してください',
            'image_path.required' => '画像を選択してください',
            'image_path.mimes' => 'JPEGもしくはPNGを選択してください',
            'post_code.required' => '郵便番号を入力してください',
            'post_code.regex' => '郵便番号をハイフン含めた８文字で入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
