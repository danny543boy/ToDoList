<?php

namespace App\Http\Requests;

use App\Models\Event;
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
            'title' => ['nullable', 'string'],
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
            'time.date' => '必須是date',
        ];
    }

    public function getUpdateArray()
    {
        $updateArray = [];
        $title = $this->validated()[Event::TITLE];
        $msg = $this->validated()[Event::MSG];
        $time = $this->validated()[Event::TIME];

        if (!is_null($title)) {
            $updateArray[Event::TITLE] = $title;
        }
        if (!is_null($msg)) {
            $updateArray[Event::MSG] = $msg;
        }
        if (!is_null($time)) {
            $updateArray[Event::TIME] = $time;
        }

        return $updateArray;
    }
}
