<?php

namespace App\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;

class DonateworthRequest extends FormRequest
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
                    //'id_bank' => 'in:0',
                    'datedont' => 'required|date_format:Y-m-d',
                    //'id_enter' => 'required|numeric|min:1',
                    //'id_proj' => 'required||not_in:0',
                    //'enterp' => 'required|not_in:0',
                    //'id_city' => 'required|numeric|min:1',
                    'id_typedont' => 'required|numeric|min:1',
                    'amount' => 'required|numeric',
                    'price' => 'required|numeric',
                    'quantity' => 'required|numeric',
                ];
                break;
            case 'POST'://Create

                $arrRules = [
                    'datedont' => 'required|date_format:Y-m-d',
                    'id_typedont' => 'required|numeric|min:1',
                    'amount' => 'required|numeric',
                    'price' => 'required|numeric',
                    'quantity' => 'required|numeric',
                ];
                //$arrRules = [];
                break;
        }
        return $arrRules;
    }
}
