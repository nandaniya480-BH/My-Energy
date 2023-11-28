<?php

namespace App\Http\Requests;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ForgotPasswordApiRequest extends FormRequest
{
    use ApiResponseTrait;
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->error('Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        throw new HttpResponseException($response);
    }
}
