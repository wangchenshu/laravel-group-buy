<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
                        'product_id' => ['required'],
                        'line_user_id' => ['required'],
                        'qty' => ['required']
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
            'product_id.required' => '產品 ID 必須填寫',
            'product_id.exists' => '產品 ID 不存在',
            'line_user_id.required' => 'Line user ID 必須填寫',
            'qty.required' => '數量必須填寫',
        ];
    }
}
