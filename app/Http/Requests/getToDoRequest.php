<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class getToDoRequest extends FormRequest
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
            "id" => ['nullable', "integer"],
            "isAll" => ['sometimes', "boolean"]
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
            'id.integer' => 'id需是整數',
            'isAll.boolean' => 'isAll必須是boolean的'
        ];
    }
}
