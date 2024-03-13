<?php

namespace App\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;

class BanksRequset extends FormRequest
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
                ];
                break;
            case 'POST'://Create

                $arrRules = [
                    //'id_bank' => 'in:0',
                    'banknumber' => 'required|numeric|min:1',
                    'bankbranch' => 'required|numeric|min:1',
                    'bankaccount' => 'required|numeric|min:1',
                    'id_enterproj' => 'required|not_in:0',
                ];
                //$arrRules = [];
                break;
        }
        return $arrRules;
    }
}
