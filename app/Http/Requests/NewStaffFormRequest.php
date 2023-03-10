<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class NewStaffFormRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasRole('system-admin')?true:false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'firstname' => 'required',
            'lastname' => 'required',
            'phoneno' => 'required|min:10',
            'password' => 'required',
            'confirmpassword' => 'required|same:password',
            'department_id' => 'required',
            'email' => 'required|email'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'firstname.required' => 'First Name is required',
            'lastname.required' => 'Last name is required',
            'phoneno.required' => 'Phone No. is required',
            'password.required' => 'Password is required',
            'confirmpassword.required' => 'Confirm Password is required',
            'department_id.required' => 'Please select a department',
            'email.required' => 'Email address is required'
        ];
    }


}