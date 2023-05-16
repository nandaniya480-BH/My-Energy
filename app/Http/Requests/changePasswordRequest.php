<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class changePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'old_password'      => 'required',
            'password'          => 'min:8|required_with:conform_password|same:conform_password',
            'conform_password'  => 'min:8|required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json(["message" => "Validation error", "status" => false, "data" => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        throw new ValidationException($validator, $response);
    }
}
