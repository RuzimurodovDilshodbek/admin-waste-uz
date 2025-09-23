@extends('layouts.admin')
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" />--}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>

<link rel="stylesheet" href="{{ asset('/administrator/summernote/summernote-bs4.css')  }}">
@section('content')

<div class="card">
    <div class="card-header">
       Xodim qo'shish
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.managementPersons.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="d-flex">
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="type" value="management" name="type" checked>
                            <label class="form-check-label" for="type">Raxbariyat</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="type" value="central_apparatus" name="type">
                            <label class="form-check-label" for="type">Markaziy apparat</label>
                        </div>
                    </div>
                </div>

                <label class="required" for="slug">Slug</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', '') }}" required>
                @if($errors->has('slug'))
                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.tutor.fields.slug_helper') }}</span>
            </div>

            <ul class="nav nav-tabs row">
                @foreach (config('app.locales') as $key_content => $value_content)
                    <li class=" nav-item">
                        <a href="#full_name_{{ $value_content }}" class="nav-link  {{ $catTab == $key_content ? 'active' : '' }} text-uppercase">{{ $value_content === 'kr' ? 'ўз' : $value_content}}</a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content row">
                @foreach (config('app.locales') as $key_content => $item_content)
                    <div class="tab-pane {{ $catTab == $key_content ? 'active' : '' }}" id="full_name_{{ $item_content }}" style="width: 100%">
                        <div class="form-group">
                            <label for="full_name">F.I.SH({{ $value_content === 'kr' ? 'ўз' : $item_content }})</label>
                            <input
                                class="form-control {{ $errors->has('full_name') ? 'is-invalid' : '' }}"
                                type="text" name="full_name_{{ $item_content }}"
                                id="full_name_{{ $item_content }}"
                                value="{{ old('full_name_', '') }}"
                            >
                            @if($errors->has('full_name'))
                                <span class="text-danger">{{ $errors->first('full_name') }}</span>
                            @endif
{{--                            <span class="help-block">{{ trans('cruds.tutor.fields.full_name_helper') }}</span>--}}
                        </div>
                    </div>
                @endforeach
            </div>

            <ul class="nav nav-tabs row">
                @foreach (config('app.locales') as $key_content => $value_content)
                    <li class=" nav-item">
                        <a href="#position_name_{{ $value_content }}" class="nav-link  {{ $catTab == $key_content ? 'active' : '' }} text-uppercase">{{ $value_content === 'kr' ? 'ўз' : $value_content}}</a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content row">
                @foreach (config('app.locales') as $key_content => $item_content)
                    <div class="tab-pane {{ $catTab == $key_content ? 'active' : '' }}" id="position_name_{{ $item_content }}" style="width: 100%">
                        <div class="form-group">
                            <label for="position_name">Lavozim({{ $item_content === 'kr' ? 'ўз' : $item_content }})</label>
                            <input
                                class="form-control {{ $errors->has('position_name') ? 'is-invalid' : '' }}"
                                type="text" name="position_name_{{ $item_content }}"
                                id="position_name_{{ $item_content }}"
                                value="{{ old('position_name_', '') }}"
                            >
                            @if($errors->has('position_name'))
                                <span class="text-danger">{{ $errors->first('position_name') }}</span>
                            @endif
{{--                            <span class="help-block">{{ trans('cruds.tutor.fields.position_name_helper') }}</span>--}}
                        </div>
                    </div>
                @endforeach
            </div>


            <div class="form-group">
                <label class="required" for="detail_image">Photo</label>
                <input type="file" name="image" class="image">
                <input type="hidden" name="image_base64">
                <img src="" style="width: 200px;display: none;" class="show-image">
            </div>

            {{-- ABOUT --}}
            <ul class="nav nav-tabs row">
                @foreach (config('app.locales') as $key_about => $value_about)
                    <li class="nav-item">
                        <a href="#tab_about_{{ $value_about }}"
                           class="nav-link {{ $catTab == $key_about ? 'active' : '' }} text-uppercase">
                            {{ $value_about === 'kr' ? 'ўз' : $value_about}}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content row">
                @foreach (config('app.locales') as $key_about => $item_about)
                    <div class="tab-pane {{ $catTab == $key_about ? 'active' : '' }}"
                         id="tab_about_{{ $item_about }}" style="width: 100%">
                        <div class="form-group">
                            <label for="about">Biografiya ({{ $item_about === 'kr' ? 'ўз' : $item_about }})</label>
                            <textarea id="about_{{ $item_about }}" class="textarea form-control summernote"
                                      style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                      name="about_{{ $item_about }}"></textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- TASKS --}}
            <ul class="nav nav-tabs row">
                @foreach (config('app.locales') as $key_tasks => $value_tasks)
                    <li class="nav-item">
                        <a href="#tab_tasks_{{ $value_tasks }}"
                           class="nav-link {{ $catTab == $key_tasks ? 'active' : '' }} text-uppercase">
                            {{ $value_tasks === 'kr' ? 'ўз' : $value_tasks}}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content row">
                @foreach (config('app.locales') as $key_tasks => $item_tasks)
                    <div class="tab-pane {{ $catTab == $key_tasks ? 'active' : '' }}"
                         id="tab_tasks_{{ $item_tasks }}" style="width: 100%">
                        <div class="form-group">
                            <label for="tasks">Vazifalari ({{ $item_tasks === 'kr' ? 'ўз' : $item_tasks }})</label>
                            <textarea id="tasks_{{ $item_tasks }}" class="textarea form-control summernote"
                                      style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                      name="tasks_{{ $item_tasks }}"></textarea>
                        </div>
                    </div>
                @endforeach
            </div>

            <ul class="nav nav-tabs row">
                @foreach (config('app.locales') as $key_content => $value_content)
                    <li class=" nav-item">
                        <a href="#address_{{ $value_content }}" class="nav-link  {{ $catTab == $key_content ? 'active' : '' }} text-uppercase">{{ $value_content === 'kr' ? 'ўз' : $value_content}}</a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content row">
                @foreach (config('app.locales') as $key_content => $item_content)
                    <div class="tab-pane {{ $catTab == $key_content ? 'active' : '' }}" id="address_{{ $item_content }}" style="width: 100%">
                        <div class="form-group">
                            <label for="address">Address({{ $value_content === 'kr' ? 'ўз' : $item_content }})</label>
                            <input
                                class="form-control {{ $errors->has('full_name') ? 'is-invalid' : '' }}"
                                type="text" name="address_{{ $item_content }}"
                                id="address_{{ $item_content }}"
                                value="{{ old('address_', '') }}"
                            >
                            @if($errors->has('address'))
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                            @endif
                            {{--                            <span class="help-block">{{ trans('cruds.tutor.fields.full_name_helper') }}</span>--}}
                        </div>
                    </div>
                @endforeach
            </div>

            <ul class="nav nav-tabs row">
                @foreach (config('app.locales') as $key_content => $value_content)
                    <li class=" nav-item">
                        <a href="#work_time_{{ $value_content }}" class="nav-link  {{ $catTab == $key_content ? 'active' : '' }} text-uppercase">{{ $value_content === 'kr' ? 'ўз' : $value_content}}</a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content row">
                @foreach (config('app.locales') as $key_content => $item_content)
                    <div class="tab-pane {{ $catTab == $key_content ? 'active' : '' }}" id="work_time_{{ $item_content }}" style="width: 100%">
                        <div class="form-group">
                            <label for="address">Ish vaqti({{ $value_content === 'kr' ? 'ўз' : $item_content }})</label>
                            <input
                                class="form-control {{ $errors->has('work_time') ? 'is-invalid' : '' }}"
                                type="text" name="work_time_{{ $item_content }}"
                                id="work_time_{{ $item_content }}"
                                value="{{ old('work_time_', '') }}"
                            >
                            @if($errors->has('work_time'))
                                <span class="text-danger">{{ $errors->first('work_time') }}</span>
                            @endif
                            {{--                            <span class="help-block">{{ trans('cruds.tutor.fields.full_name_helper') }}</span>--}}
                        </div>
                    </div>
                @endforeach
            </div>


            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ old('email', '') }}">
                @if($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
{{--                <span class="help-block">{{ trans('cruds.tutor.fields.email_helper') }}</span>--}}
            </div>

            <div class="form-group">
                <label for="phone">Telefon raqam</label>
                <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" name="phone" id="phone" value="{{ old('phone', '') }}">
                @if($errors->has('phone'))
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
{{--                <span class="help-block">{{ trans('cruds.tutor.fields.phone_helper') }}</span>--}}
            </div>

            <div class="form-group">
                <label for="sort">Saralash</label>
                <input class="form-control {{ $errors->has('sort') ? 'is-invalid' : '' }}" type="number" name="sort" id="sort" value="{{ old('sort', '1') }}" step="1">
                @if($errors->has('sort'))
                    <span class="text-danger">{{ $errors->first('sort') }}</span>
                @endif
{{--                <span class="help-block">{{ trans('cruds.tutor.fields.sort_helper') }}</span>--}}
            </div>

            <div class="form-group">
                <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="status" value="0">
                    <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', 0) == 1 || old('status') === null ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">Holati</label>
                </div>
                @if($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    Saqlash
                </button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Расмни қуйидагича қирқиб олинг</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                        </div>
                        <div class="col-md-4">
                            <div class="preview"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Бекор қилиш</button>
                <button type="button" class="btn btn-primary" id="crop">Қирқиш</button>
            </div>
        </div>
    </div>
</div>




@endsection
@section('js')
    <script src="{{ asset('/administrator/select2-bootstrap4-theme/select2-bootstrap4.min.css')  }}"></script>
    <script>
        $('.select2').select2()
    </script>
    <script src="{{ asset('/administrator/summernote/summernote-bs4.min.js')  }}"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 300,
            });
        });
        $(document).ready(function(){
            $(".nav-tabs a").click(function(){
                $(this).tab('show');
            });
        });
    </script>
@stop

@section('scripts')
    <script src="{{ asset('/js/global.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 300,
            });
        });
        $(document).ready(function(){
            $(".nav-tabs a").click(function(){
                $(this).tab('show');
            });
        });
    </script>
    <script src="{{ asset('/administrator/summernote/summernote-bs4.min.js')  }}"></script>

    <script>
        $('.select2').select2()
    </script>
    <script src="{{ asset('/administrator/select2-bootstrap4-theme/select2-bootstrap4.min.css')  }}"></script>

    <script src="{{ asset('/js/translator.js')  }}"></script>
    <script>
        var $modal = $('#modal');
        var image = document.getElementById('image');
        var cropper;

        /*------------------------------------------
        --------------------------------------------
        Image Change Event
        --------------------------------------------
        --------------------------------------------*/
        $("body").on("change", ".image", function(e){
            var files = e.target.files;
            var done = function (url) {
                image.src = url;
                $modal.modal('show');
            };

            var reader;
            var file;
            var url;

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        /*------------------------------------------
        --------------------------------------------
        Show Model Event
        --------------------------------------------
        --------------------------------------------*/
        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
                preview: '.preview'
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });

        /*------------------------------------------
        --------------------------------------------
        Crop Button Click Event
        --------------------------------------------
        --------------------------------------------*/
        $("#crop").click(function(){
            canvas = cropper.getCroppedCanvas({
                width: 1920,
                height: 1280,
            });

            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    $("input[name='image_base64']").val(base64data);
                    $(".show-image").show();
                    $(".show-image").attr("src",base64data);
                    $("#modal").modal('toggle');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            function SimpleUploadAdapter(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                    return {
                        upload: function() {
                            return loader.file
                                .then(function (file) {
                                    return new Promise(function(resolve, reject) {
                                        // Init request
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST', '{{ route('admin.posts.storeCKEditorImages') }}', true);
                                        xhr.setRequestHeader('x-csrf-token', window._token);
                                        xhr.setRequestHeader('Accept', 'application/json');
                                        xhr.responseType = 'json';

                                        // Init listeners
                                        var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                                        xhr.addEventListener('error', function() { reject(genericErrorText) });
                                        xhr.addEventListener('abort', function() { reject() });
                                        xhr.addEventListener('load', function() {
                                            var response = xhr.response;

                                            if (!response || xhr.status !== 201) {
                                                return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                                            }

                                            $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                                            resolve({ default: response.url });
                                        });

                                        if (xhr.upload) {
                                            xhr.upload.addEventListener('progress', function(e) {
                                                if (e.lengthComputable) {
                                                    loader.uploadTotal = e.total;
                                                    loader.uploaded = e.loaded;
                                                }
                                            });
                                        }

                                        // Send request
                                        var data = new FormData();
                                        data.append('upload', file);
                                        data.append('crud_id', '{{ $post->id ?? 0 }}');
                                        xhr.send(data);
                                    });
                                })
                        }
                    };
                }
            }
            var allEditors = document.querySelectorAll('.ckeditor');
            for (var i = 0; i < allEditors.length; ++i) {
                ClassicEditor.create(
                    allEditors[i], {
                        extraPlugins: [SimpleUploadAdapter]
                    }
                );
            }

            $(".nav-tabs a").click(function(e){
                e.preventDefault();
                $(this).tab('show');
            });
        });
    </script>
    <script>
        Dropzone.options.detailImageDropzone = {
            url: '{{ route('admin.posts.storeMedia') }}',
            maxFilesize: 15, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 15,
                width: 160,
                height: 160
            },
            success: function (file, response) {
                $('form').find('input[name="detail_image"]').remove()
                $('form').append('<input type="hidden" name="detail_image" value="' + response.name + '">')
            },
            removedfile: function (file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="detail_image"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function () {
                @if(isset($post) && $post->detail_image)
                var file = {!! json_encode($post->detail_image) !!}
                this.options.addedfile.call(this, file)
                this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                file.previewElement.classList.add('dz-complete')
                $('form').append('<input type="hidden" name="detail_image" value="' + file.file_name + '">')
                this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function (file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
    <script>
        Dropzone.options.cardImageDropzone = {
            url: '{{ route('admin.posts.storeMedia') }}',
            maxFilesize: 10, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 10,
                width: 160,
                height: 160
            },
            success: function (file, response) {
                $('form').find('input[name="card_image"]').remove()
                $('form').append('<input type="hidden" name="card_image" value="' + response.name + '">')
            },
            removedfile: function (file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="card_image"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function () {
                @if(isset($post) && $post->card_image)
                var file = {!! json_encode($post->card_image) !!}
                this.options.addedfile.call(this, file)
                this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                file.previewElement.classList.add('dz-complete')
                $('form').append('<input type="hidden" name="card_image" value="' + file.file_name + '">')
                this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function (file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            // $('.summernote').summernote({
            //     // 'justifyFull',
            //     textAlign: 'justify',
            // });
            // $('.summernote').forEach((element) => {
            //     element.summernote('justifyFull');
            // });
            // $('#tab_kr').summernote('justifyFull');

            function translate(element, inputValue) {
                const locales = @json($locales);
                $.ajax({
                    url: '{{ route('translate') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        data: inputValue
                    },
                    success: function (response) {
                        locales.forEach((locale, index) => {
                            if(locale !== 'kr') {
                                $('#' + element + '_' + locale).val(response.data[index-1]);
                            }
                        })
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
            $('#title_kr').on('blur', function () {
                var inputValue = $(this).val();
                const translatedValue = cyrToLat(inputValue);
                if(translatedValue) {
                    translate('title', translatedValue)
                }
            });
            $('#description_kr').on('blur', function () {
                var inputValue = $(this).val();
                const translatedValue = cyrToLat(inputValue);
                if(translatedValue) {
                    translate('description', translatedValue)
                }
            });
            {{--$('#tab_kr').find('.note-editable.card-block').on('blur', function () {--}}
            {{--    //     const element = document.getElementById('tab_kr').getElementsByClassName('note-editable')[0];--}}
            {{--    // const element = $('#tab_kr').find('.note-editable.card-block');--}}
            {{--    const element = $(this)[0];--}}

            {{--    console.log(element);--}}

            {{--    if(element) {--}}
            {{--        const locales = @json($locales);--}}
            {{--        $.ajax({--}}
            {{--            url: '{{ route('translate') }}',--}}
            {{--            method: 'POST',--}}
            {{--            data: {--}}
            {{--                _token: '{{ csrf_token() }}',--}}
            {{--                data: element.outerHTML--}}
            {{--            },--}}
            {{--            success: function (response) {--}}
            {{--                console.log(response);--}}
            {{--                locales.forEach((locale, index) => {--}}
            {{--                    if(locale !== 'kr') {--}}
            {{--                        // let changedElement = $('#tab_' + locale).find('.note-editable.card-block')[0];--}}
            {{--                        if(document.getElementById('tab_' + locale).getElementsByClassName('note-editable')[0]) {--}}
            {{--                            document.getElementById('tab_' + locale).getElementsByClassName('note-editable')[0].outerHTML = response.data[index-1];--}}
            {{--                        }--}}
            {{--                    }--}}
            {{--                })--}}
            {{--            },--}}
            {{--            error: function (xhr, status, error) {--}}
            {{--                console.error(xhr.responseText);--}}
            {{--            }--}}
            {{--        });--}}
            {{--    }--}}
            {{--});--}}
        })
    </script>
@endsection
<style>
    .label-for-checkbox{
        font-weight: 700;
        font-family: "Source Sans Pro",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
        font-size: 1rem;
        line-height: 1.5;
        color: #212529;
        text-align: lef
    }
</style>
<style type="text/css">
    body{
        background: #f7fbf8;
    }
    h1{
        font-weight: bold;
        font-size:23px;
    }
    img {
        display: block;
        max-width: 100%;
    }
    .preview {
        text-align: center;
        overflow: hidden;
        width: 240px!important;
        height: 160px;
        margin: 10px;
        border: 1px solid red;
    }
    input{
        margin-top:40px;
    }
    .section{
        margin-top:150px;
        background:#fff;
        padding:50px 30px;
    }
    .modal-lg{
        max-width: 1000px !important;
    }
    .hidden-button{
        display: none;
    }
</style>
