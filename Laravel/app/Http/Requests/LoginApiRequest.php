<?php

namespace App\Http\Requests;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;


class LoginApiRequest extends FormRequest
{
    use ApiResponseTrait;
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
            'email'      => 'required_without:user_name|email',
            'user_name'  => 'required_without:email|string',
            'password'   => 'required|min:8',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->error('Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        throw new HttpResponseException($response);
    }
}
