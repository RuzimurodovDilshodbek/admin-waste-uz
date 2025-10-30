<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTutorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tutor_edit');
    }

    public function rules()
    {
        return [
            'slug' => [
                'string',
                'required',
                'max:255',
            ],
            'full_name_uz' => [
                'string',
                'nullable',
                'max:255',
            ],
            'full_name_kr' => [
                'string',
                'nullable',
                'max:255',
            ],
            'full_name_ru' => [
                'string',
                'nullable',
                'max:255',
            ],
            'full_name_en' => [
                'string',
                'nullable',
                'max:255',
            ],
            'position_name_uz' => [
                'string',
                'nullable',
                'max:255',
            ],
            'position_name_kr' => [
                'string',
                'nullable',
                'max:255',
            ],
            'position_name_ru' => [
                'string',
                'nullable',
                'max:255',
            ],
            'position_name_en' => [
                'string',
                'nullable',
                'max:255',
            ],
            'about_uz' => [
                'string',
                'nullable',
            ],
            'about_kr' => [
                'string',
                'nullable',
            ],
            'about_ru' => [
                'string',
                'nullable',
            ],
            'about_en' => [
                'string',
                'nullable',
            ],
            'tasks_uz' => [
                'string',
                'nullable',
            ],
            'tasks_kr' => [
                'string',
                'nullable',
            ],
            'tasks_ru' => [
                'string',
                'nullable',
            ],
            'tasks_en' => [
                'string',
                'nullable',
            ],
            'phone' => [
                'string',
                'nullable',
                'max:255',
            ],
            'email' => [
                'string',
                'nullable',
                'email',
                'max:255',
            ],
            'address_uz' => [
                'string',
                'nullable',
            ],
            'address_kr' => [
                'string',
                'nullable',
            ],
            'address_ru' => [
                'string',
                'nullable',
            ],
            'address_en' => [
                'string',
                'nullable',
            ],
            'work_time_uz' => [
                'string',
                'nullable',
            ],
            'work_time_kr' => [
                'string',
                'nullable',
            ],
            'work_time_ru' => [
                'string',
                'nullable',
            ],
            'work_time_en' => [
                'string',
                'nullable',
            ],
            'sort' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'status' => [
                'boolean',
                'nullable',
            ],
            'type' => [
                'string',
                'nullable',
                'max:255',
            ],
        ];
    }
}
