<?php

namespace App\Http\Requests;

use App\Moduleresourcebank;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreModuleresourcebankRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('moduleresourcebank_create');
    }

    public function rules()
    {
        return [
            'module_id' => [
                'required',
                'integer',
            ],
            'chapterid_id' => [
                'required',
                'integer',
            ],
            'title' => [
                'string',
                'required',
            ],
            'link' => [
                'string',
                'nullable',
            ],
            'resourcefile' => [
                'array',
            ],
            'resource_photos' => [
                'array',
            ],
        ];
    }
}