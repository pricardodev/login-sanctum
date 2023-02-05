<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        if(request()->routeIs('usuario.atualizar')){
            $passwordRequired = '';
        }else{
            $passwordRequired = 'required';
        }

        return [
            'name' => 'required',
            'birth_date' => 'required',
            'main_contact' => 'required',
            'secondary_contact' => 'nullable',
            'user_image' => 'nullable',
            'email' => 'email|required|unique:App\Models\User,email,'.$this->id,
            'password' => [
                $passwordRequired,
                Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],
            'status' => [Rule::in([1, 0]), 'required'],
        ];
    }

    protected function prepareForValidation()
    {
        if($this->password == null){
            $this->request->remove('password');
        }
    }
}
