<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
        return [];
    }

    public function messages()
    {
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $itemId = $this->route('item_id');

            $method = session("method_{$itemId}");
            $delivery = session("delivery_temp_{$itemId}");

            if (!$method) {
                $validator->errors()->add('method', '支払い方法を選択してください');
            }

            if (
                !$delivery || empty($delivery['post_code']) || empty($delivery['address'])
            ) {
                $validator->errors()->add('delivery', '配送先住所を登録してください');
            }
        });
    }
}
