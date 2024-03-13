<?php

namespace App\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;

class BankslineRequset extends FormRequest
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
                    'datemovement' => 'required|date_format:Y-m-d',
                    'description' => 'required',
                    'asmcta' => 'required|numeric|min:1',
                    'amountmandatory' => 'required|numeric',
                    'amountright' => 'required|numeric',
                    'id_titletwo' => 'required|numeric|min:0',
                    'id_enter' => 'required|numeric|min:0',
                ];
                break;
            case 'POST'://Create
                $arrRules = [
                    'datemovement' => 'required|date_format:Y-m-d',
                    'description' => 'required',
                    'asmcta' => 'required|numeric|min:1',
                    'amountmandatory' => 'required|numeric',
                    'amountright' => 'required|numeric',
                    'id_titletwo' => 'required|numeric|min:0',
                    'id_enter' => 'required|numeric|min:0',
                ];
                //$arrRules = [];
                break;
        }
        return $arrRules;
    }
}
