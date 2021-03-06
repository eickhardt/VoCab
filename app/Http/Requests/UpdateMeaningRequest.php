<?php namespace App\Http\Requests;

use Session;

class UpdateMeaningRequest extends Request
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
        return [
            'meaning_type_id' => 'required|exists:meaning_types,id',
            'root' => 'required',
        ];
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
