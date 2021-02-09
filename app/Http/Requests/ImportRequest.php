<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
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
//        $rules = [
//            'csv_file' => 'required|file|max:2000000',
//            'ignore_header' => 'required|boolean',
//            'column1' => 'required|exists:word_languages,id',
//        ];

//        for ($i = 2; $i <= config('app.max_active_languages'); $i++) {
//            $rules['column' . $i] = 'exists:word_languages,id';
//        }

        return ['csv_file' => 'required|file|max:2000000'];
    }
}
