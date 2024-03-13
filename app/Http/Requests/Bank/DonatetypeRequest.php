<?php

namespace App\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;

class DonatetypeRequest extends FormRequest
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
        //return [];
        switch ($this->method()) {
            case 'GET'://Read
            case 'PATCH'://Update/Modify
            case 'DELETE'://Delete
                $arrRules = [
                ];
                break;
            case 'PUT'://Update/Replace
                $arrRules = [
                    'price' => 'required|numeric|min:1',
                ];
                break;
            case 'POST'://Create

                $arrRules = [
                    'name' => 'required',
                    'price' => 'required|numeric|min:1',
                ];
                //$arrRules = [];
                break;
        }
        return $arrRules;
    }
}
