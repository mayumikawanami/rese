<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
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
            'reservation_id' => 'required|exists:reservations,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'reservation_id.required' => '予約を選択してください',
            'reservation_id.exists' => '選択した予約は存在しません',
            'rating.required' => '評価を選択してください',
            'rating.integer' => '評価は整数である必要があります',
            'rating.min' => '評価は1以上である必要があります',
            'rating.max' => '評価は5以下である必要があります',
            'comment.string' => 'コメントを文字列で入力してください',
            'comment.max' => 'コメントは255文字以下で入力してください',
        ];
    }
}
