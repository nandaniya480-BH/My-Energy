<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
    public function rules(): array
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
}
