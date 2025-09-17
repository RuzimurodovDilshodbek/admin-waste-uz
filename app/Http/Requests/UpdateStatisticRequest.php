<?php

namespace App\Http\Requests;

use App\Models\Newsletter;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateStatisticRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('statistic_edit');
    }

    public function rules()
    {
        return [
            'name_uz' => [
                'string',
                'nullable',
            ],
            'name_ru' => [
                'string',
                'nullable',
            ],
            'name_en' => [
                'string',
                'nullable',
            ],
            'count' => [
                'integer',
                'nullable',
            ],
        ];
    }
}
