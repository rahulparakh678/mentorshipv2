<?php

namespace App\Http\Requests;

use App\Languagespoken;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreLanguagespokenRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('languagespoken_create');
    }

    public function rules()
    {
        return [
            'langname' => [
                'string',
                'required',
            ],
        ];
    }
}