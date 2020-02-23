<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class FbUserRequest extends FormRequest
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
                        'messenger_user_id' => ['required', 'max:100', 'unique:fb_user'],
                        'first_name' => ['required'],
                        'last_name' => ['required']
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
            'messenger_user_id.exists' => 'Messenger User ID 不存在',
            'first_name.required' => 'First Name 必須填寫',
            'last_name.required' => 'Last Name 必須填寫',
        ];
    }
}
