<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ChatfuelAddCartRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET': {
                    return [
                        'id' => ['required, id']
                    ];
                }
            case 'POST': {
                    return [
                        'messenger_user_id' => ['required'],
                        'product_name' => ['required'],
                        'username' => ['required'],
                        'qty' => ['required'],
                        'price' => ['required']
                    ];
                }
            case 'PUT':
            case 'PATCH':
            case 'DELETE':
            default: {
                    return [];
                }
        }
    }

    public function messages()
    {
        return [
            'messenger_user_id.required' => 'Messenger User ID 必須填寫',
            'product_name.required' => '產品名稱必須填寫',
            'username.required' => '用戶名稱必須填寫',
            'qty.required' => '數量必須填寫',
            'price.required' => '價格必須填寫',
        ];
    }
}
