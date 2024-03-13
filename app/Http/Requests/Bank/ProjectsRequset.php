<?php

namespace App\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;

class ProjectsRequset extends FormRequest
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
                    'id_entrp' => 'required|exists:App\Models\bank\Enterprise,id',
                    'name' => 'required',
                ];

                break;
        }
        return $arrRules;
    }
}
