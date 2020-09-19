<?php namespace App\Http\Requests;

use App\WordLanguage;
use Session;

class CreateMeaningRequest extends Request
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
     * Respond accordingly if the user is not authorized.
     */
    public function forbiddenResponse()
    {
        Session::flash('error', "You don't have permission to do that.");
        return $this->redirector->back();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $languages = WordLanguage::all();

        $rules = [
            'root' => 'required',
            'real_word_type' => 'required|integer|min:100|max:999',
            'meaning_type_id' => 'required|exists:meaning_types,id'
        ];

        foreach ($languages as $language) {
            $rules[$language->short_name] = 'string|nullable';
        }

        return $rules;
    }

    /**
     * Get the sanitized input for the request.
     *
     * @return array
     */
    public function sanitize()
    {
        return $this->all();
    }
}
