@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.tutor.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.managementPersons.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            ID
                        </th>
                        <td>
                            {{ $tutor->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Slug
                        </th>
                        <td>
                            {{ $tutor->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            F.I.SH
                        </th>
                        <td>
                            {{ $tutor->full_name_uz }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Lavozim
                        </th>
                        <td>
                            {{ $tutor->position_name_uz }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Photo
                        </th>
                        <td>
                            @if($tutor->photo)
                                <a href="{{ $tutor->photo->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $tutor->photo->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
{{--                    <tr>--}}
{{--                        <th>--}}
{{--                            Biografiya--}}
{{--                        </th>--}}
{{--                        <td>--}}
{{--                            {{ $tutor->about_uz }}--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th>--}}
{{--                            Vazifalari--}}
{{--                        </th>--}}
{{--                        <td>--}}
{{--                            {{ $tutor->tasks_uz }}--}}
{{--                        </td>--}}
{{--                    </tr>--}}
                    <tr>
                        <th>
                            Email
                        </th>
                        <td>
                            {{ $tutor->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Telefon
                        </th>
                        <td>
                            {{ $tutor->phone }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Saralash
                        </th>
                        <td>
                            {{ $tutor->sort }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Holati
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $tutor->status ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.managementPersons.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
