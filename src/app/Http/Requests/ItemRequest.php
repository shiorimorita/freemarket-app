<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
            'name' => ['required'],
            'description' => ['required','max:255'],
            'image_path' => ['required','mimes:jpeg,png'],
            'condition' => ['required'],
            'price'=> ['required','integer','min:0'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '詳細を入力してください',
            'description.max' => '詳細は255文字以下で入力してください',
            'image_path.required' => '画像を選択してください',
            'image_path.mimes' => '画像はjpeg、png形式を選択してください',
            'condition.required' => '状態を入力してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '価格は数値で入力してください',
            'price.min' => '価格は0円以上で入力してください',
        ];
    }
}
