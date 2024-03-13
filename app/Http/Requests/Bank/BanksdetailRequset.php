<?php

namespace App\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;

class BanksdetailRequset extends FormRequest
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
        $arrRules = [];
        //return $arrRules;
        //ddd($this->fullUrl());
        switch ($this->method()) {
            //case 'GET'://Read
            //case 'PATCH'://Update/Modify
            //case 'DELETE'://Delete
            case 'PUT'://Update/Replace
                $arrRules = [
                    'scome' => 'required|numeric|min:1',
                    'proj' => 'required|not_in:0',
                    'city' => 'required|not_in:0',
                    //'incmexpe' => 'required',//|not_in:0
                ];
                break;
            case 'POST'://Create
                $arrRules = [
                    'scome' => 'required|numeric|min:1',
                    'proj' => 'required|not_in:0',
                    'city' => 'required|not_in:0',
                    //'incmexpe' => 'required',//|not_in:0
                ];
                //$arrRules = [];
                break;
        }
        return $arrRules;
    }
}
