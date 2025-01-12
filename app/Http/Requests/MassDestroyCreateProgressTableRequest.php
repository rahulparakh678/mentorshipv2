<?php

namespace App\Http\Requests;

use App\CreateProgressTable;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCreateProgressTableRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('create_progress_table_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:create_progress_tables,id',
        ];
    }
}
