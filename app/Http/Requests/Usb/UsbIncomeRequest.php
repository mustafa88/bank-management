<?php

namespace App\Http\Requests\Usb;

use Illuminate\Foundation\Http\FormRequest;

class UsbIncomeRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET'://Read
            case 'PATCH'://Update/Modify
            case 'DELETE'://Delete
                $arrRules = [
                ];
                break;
            case 'PUT'://Update/Replace
                $arrRules = [
                    //'dateincome' => 'required|date_format:Y-m-d',
                    'nameclient'=> 'required',
                    'amount' => 'required|numeric|min:1',
                    'id_curn' => 'required|numeric|min:1',
                    'id_titletwo' => 'required|numeric|min:1',
                    'id_incom' => 'required|numeric|min:1',
                    'kabala' => 'required|numeric|min:1',
                    'kabladat' => 'required|date_format:Y-m-d',
                    //'phone' => 'numeric',
                    'nameovid' => 'required',
                ];
                break;
            case 'POST'://Create

                $arrRules = [
                    //'dateincome' => 'required|date_format:Y-m-d',
                    'nameclient'=> 'required',
                    'amount' => 'required|numeric|min:1',
                    'id_curn' => 'required|numeric|min:1',
                    'id_titletwo' => 'required|numeric|min:1',
                    'id_incom' => 'required|numeric|min:1',
                    'kabala' => 'required|numeric|min:1',
                    'kabladat' => 'required|date_format:Y-m-d',
                    //'phone' => 'numeric',
                    'nameovid' => 'required',
                ];
                //$arrRules = [];
                break;
        }
        return $arrRules;
    }
}
