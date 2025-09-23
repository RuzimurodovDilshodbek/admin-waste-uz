<?php

namespace App\Http\Requests;

use App\Models\Tutor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTutorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tutor_create');
    }

    public function rules()
    {
        return [
            'slug' => [
                'string',
                'required',
            ],
            'full_name_uz' => [
                'string',
                'required',
            ],
            'full_name_kr' => [
                'string',
                'nullable',
            ],
            'full_name_ru' => [
                'string',
                'nullable',
            ],
            'full_name_en' => [
                'string',
                'nullable',
            ],
            'position_name_uz' => [
                'string',
                'required',
            ],
            'position_name_kr' => [
                'string',
                'nullable',
            ],
            'position_name_ru' => [
                'string',
                'nullable',
            ],
            'position_name_en' => [
                'string',
                'nullable',
            ],
            'image_base64' => [
                'required',
            ],

            'sort' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
