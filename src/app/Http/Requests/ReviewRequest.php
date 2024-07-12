<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:400',
            'image_path' => 'nullable|mimes:jpeg,png',
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '評価を選択してください',
            'content.required' => '口コミを入力してください',
            'image_path.mimes' => '画像はjpeg、またはpng形式でアップロードしてください。',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->hasFile('image_path')) {
            $image = $this->file('image_path');
            $path = $image->store('temp', 'public');
            $this->merge([
                'image_path' => $path,
            ]);
        }
    }
}
