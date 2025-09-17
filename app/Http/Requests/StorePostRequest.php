<?php

namespace App\Http\Requests;

use App\Models\Post;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StorePostRequest extends FormRequest
{

    public function authorize()
    {
        return Gate::allows('post_create');
    }

    public function rules(Request $request)
    {
        return [
            'title_uz' => [
                'required',
                'string',
//                function ($attribute, $value, $fail) use ($request) {
//                    if (in_array('uz', $request->langs) && empty($value)) {
//                        $fail(trans('validation.required', ['attribute' => trans('validation.attributes.title_uz')]));
//                    }
//                },
            ],
            'title_ru' => [
                'nullable',
                'string',
//                function ($attribute, $value, $fail) use ($request) {
//                    if (in_array('ru', $request->langs) && empty($value)) {
//                        $fail(trans('validation.required', ['attribute' => trans('validation.attributes.title_ru')]));
//                    }
//                },
            ],
            'title_en' => [
                'nullable',
                'string',
//                function ($attribute, $value, $fail) use ($request) {
//                    if (in_array('en', $request->langs) && empty($value)) {
//                        $fail(trans('validation.required', ['attribute' => trans('validation.attributes.title_en')]));
//                    }
//                },
            ],
            'status' => [
                'string',
                'required',
            ],
            'image_base64' => [
                'string',
                'nullable',
            ],

            'content_uz' => [
                'required',
                'string',
//                function ($attribute, $value, $fail) use ($request) {
//                    if (in_array('uz', $request->langs) && empty($value)) {
//                        $fail(trans('validation.required', ['attribute' => trans('validation.attributes.content_uz')]));
//                    }
//                },
            ],
            'content_ru' => [
                'nullable',
                'string',
//                function ($attribute, $value, $fail) use ($request) {
//                    if (in_array('ru', $request->langs) && empty($value)) {
//                        $fail(trans('validation.required', ['attribute' => trans('validation.attributes.content_ru')]));
//                    }
//                },
            ],
            'content_en' => [
                'nullable',
                'string',
//                function ($attribute, $value, $fail) use ($request) {
//                    if (in_array('en', $request->langs) && empty($value)) {
//                        $fail(trans('validation.required', ['attribute' => trans('validation.attributes.content_en')]));
//                    }
//                },
            ],
            'section_ids' => [
                'array',
                'required',
            ],
            'tags' => [
                'array',
            ],
            'publish_date' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'langs' => [
                'array',
                'nullable',
            ],
        ];
    }
}
