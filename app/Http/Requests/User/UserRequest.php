<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'fullName' => 'required',
            'role_id' => 'required',
        ] + ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store()
    {
        return [
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:8|confirmed',
        ];
    }

    protected function update()
    {
        return [
            'email' => 'bail|required|email|unique:users,email,'.$this->id,
        ];
    }
}
