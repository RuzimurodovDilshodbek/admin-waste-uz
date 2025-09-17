@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} Statistikalar
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.statistics.update', $statistic->id) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <div class="form-group">
                    <label for="name">Nomi</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           type="text"
                           name="name"
                           id="name"
                           disabled
                           value="{{ old('name', $statistic->name_uz) }}"
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="count">Soni</label>
                    <input class="form-control {{ $errors->has('count') ? 'is-invalid' : '' }}"
                           type="number"
                           name="count"
                           id="count"
                           value="{{ old('count', $statistic->count) }}"
                           required>
                    @if($errors->has('count'))
                        <div class="invalid-feedback">
                            {{ $errors->first('count') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
