<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//C:\Users\Cliente\Desktop\gustavo\vitafor\vitafor_crud_test\rick-and-morty\app\Http\Requests\StoreCharacterRequest.php

class StoreCharacterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'image' => 'required|url',
            'url' => 'required|url',
        ];
    }
}
