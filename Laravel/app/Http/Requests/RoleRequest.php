<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;

class RoleRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->request->get('id');

        if (!empty($id)) {
            $rules = array(
                'id' => 'required',
                'name' => 'required|unique:roles,name,' . $id,
                'permission' => 'required',
            );
        } else {
            $rules = array(
                'name' => 'required|unique:roles,name',
                'permission' => 'required',
            );
        }
        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->error('Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY, $validator->errors());
        throw new HttpResponseException($response);
    }
}
