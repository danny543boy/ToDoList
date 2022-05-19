<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class newToDoRequest extends FormRequest
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
            'title' => ['required', 'nullable', 'string'],
            'msg' => ['sometimes', 'nullable', 'string'],
            'time' => ['sometimes', 'nullable', 'date'],
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
            'title.required' => '標題是必填的，',
            'title.string' => '標題必須是string的',
            'msg.string'  => '訊息必須是string',
            'time' => '必須是date'
        ];
    }
}
