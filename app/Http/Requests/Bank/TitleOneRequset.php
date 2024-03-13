<?php

namespace App\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;

class TitleOneRequset extends FormRequest
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
        //https://www.restapitutorial.com/lessons/httpmethods.html
        //required | max:X | numneric | unique:TABEL,COLUMN | email
        // all validation => https://laravel.com/docs/8.x/validation#available-validation-rules
        $arrRules = [];
        //return $arrRules;
        //ddd($this->method());
        //$this->path()
        switch ($this->method()) {
            case 'GET'://Read
            case 'PATCH'://Update/Modify
            case 'DELETE'://Delete
                //$arrRules = [];
                $arrRules = [

                ];
                break;
            case 'PUT'://Update/Replace
                $arrRules = [

                ];
                //$arrRules = [];
                break;
            case 'POST'://Create

                $arrRules = [
                    'tone_text' => 'required',
                ];

                break;
        }
        return $arrRules;

    }
}
