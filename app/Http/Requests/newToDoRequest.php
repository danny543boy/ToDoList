<?php

namespace App\Http\Requests;

use App\Models\Event;
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
            'title' => ['required', 'string'],
            'msg' => ['nullable', 'string'],
            'time' => ['nullable', 'date'],
        ];
    }

    public function getInsertArray()
    {
        $title = $this->validated()[Event::TITLE];
        $msg = $this->validated()[Event::MSG];
        $time = $this->validated()[Event::TIME];

        $result = [
            Event::TITLE => $title,
            Event::MSG => $msg,
            Event::TIME => $time,
        ];

        return $result;
    }
}
