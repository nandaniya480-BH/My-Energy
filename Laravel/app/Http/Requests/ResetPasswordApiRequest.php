<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ResetPasswordApiRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'token'     => 'required',
            'password'  => 'required|min:8|confirmed',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json(["message" => "Validation error", "status" => false, "data" => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        throw new ValidationException($validator, $response);
    }
}
