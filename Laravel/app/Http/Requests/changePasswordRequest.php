<?php

namespace App\Http\Requests;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class changePasswordRequest extends FormRequest
{
    use ApiResponseTrait;
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
        $response = $this->error('Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        throw new HttpResponseException($response);
    }
}
