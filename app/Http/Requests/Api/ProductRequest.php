<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
                        'name' => ['required'],
                        'link' => ['required'],
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
            'name.required' => '產品名稱必須填寫',
            'name.exists' => '產品名稱不存在',
            'link.required' => '產品連結必須填寫',
            'price.required' => '產品價格必須填寫',
        ];
    }
}
