<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateToDoRequest extends FormRequest
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
            'title' => ['string'],
            'msg' => ['nullable', 'string'],
            'time' => ['nullable', 'date'],
        ];
    }

    /**
     * 取得已定義驗證規則的錯誤訊息。
     *
     * @return array
     */
    public function messages()
    {
        // $this->setValue($this);
        return [
            'title.string' => '標題必須是string的',
            'msg.string' => '訊息必須是string',
            'time.date' => 'time必須是date',
        ];
    }

}
