<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
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
            'radio'=>['required'],
            'file'=>['required','file'],
            'file_name'=>['required','string','min:1','max:255']
        ];
    }

    public function messages()
    {
        return [
            'file_name.required'=>'You need to enter name for file!',
            'file.required'=>'You need to choose file to add!'
        ];
    }
}
