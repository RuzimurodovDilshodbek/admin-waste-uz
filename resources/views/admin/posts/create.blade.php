@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css"/>
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
/* ===== QUILL EDITOR ===== */
.quill-wrap {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    transition: border-color 0.2s;
}
.quill-wrap:focus-within { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,0.1); }
.ql-toolbar.ql-snow {
    border: none !important;
    border-bottom: 1px solid #f1f5f9 !important;
    background: #f8fafc;
    padding: 8px 10px;
    flex-wrap: wrap;
}
.ql-container.ql-snow {
    border: none !important;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
}
.ql-editor {
    min-height: 380px;
    padding: 16px 18px;
    color: #1e293b;
    line-height: 1.7;
}
.ql-editor.ql-blank::before {
    color: #94a3b8;
    font-style: normal;
    font-size: 14px;
}
.ql-snow .ql-picker.ql-header .ql-picker-label::before,
.ql-snow .ql-picker.ql-header .ql-picker-item::before { content: 'Normal'; }
.ql-snow .ql-picker.ql-header .ql-picker-label[data-value="1"]::before,
.ql-snow .ql-picker.ql-header .ql-picker-item[data-value="1"]::before { content: 'Sarlavha 1'; }
.ql-snow .ql-picker.ql-header .ql-picker-label[data-value="2"]::before,
.ql-snow .ql-picker.ql-header .ql-picker-item[data-value="2"]::before { content: 'Sarlavha 2'; }
.ql-snow .ql-picker.ql-header .ql-picker-label[data-value="3"]::before,
.ql-snow .ql-picker.ql-header .ql-picker-item[data-value="3"]::before { content: 'Sarlavha 3'; }
.ql-snow .ql-picker.ql-header .ql-picker-label[data-value="4"]::before,
.ql-snow .ql-picker.ql-header .ql-picker-item[data-value="4"]::before { content: 'Sarlavha 4'; }
.ql-toolbar .ql-formats { margin-right: 8px; }
.ql-snow .ql-stroke { stroke: #475569; }
.ql-snow .ql-fill { fill: #475569; }
.ql-snow .ql-picker { color: #475569; }
.ql-snow.ql-toolbar button:hover .ql-stroke,
.ql-snow.ql-toolbar button.ql-active .ql-stroke { stroke: #16a34a; }
.ql-snow.ql-toolbar button:hover .ql-fill,
.ql-snow.ql-toolbar button.ql-active .ql-fill { fill: #16a34a; }
.ql-snow.ql-toolbar button:hover,
.ql-snow.ql-toolbar button.ql-active {
    background: #f0fdf4 !important;
    border-radius: 5px;
    color: #16a34a;
}
.ql-snow .ql-picker-label:hover,
.ql-snow .ql-picker-label.ql-active { color: #16a34a; }
</style>
<style>
/* ===== LAYOUT ===== */
.post-form-wrap {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 20px;
    align-items: start;
}

.post-page-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}

.post-page-back {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: white;
    border: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: center;
    color: #64748b; text-decoration: none;
    transition: all 0.2s; font-size: 13px;
    flex-shrink: 0;
}
.post-page-back:hover { background: #f0fdf4; border-color: #16a34a; color: #16a34a; }

.post-page-title { font-size: 20px; font-weight: 800; color: #0f172a; letter-spacing: -0.3px; margin: 0; }

/* ===== FORM SECTION ===== */
.form-section {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}

.form-section-header {
    padding: 13px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; gap: 10px;
}

.form-section-icon {
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0;
}

.form-section-title { font-size: 13px; font-weight: 700; color: #0f172a; margin: 0; }
.form-section-body { padding: 18px 20px; }

/* ===== STATUS TOGGLE ===== */
.status-toggle { display: flex; gap: 8px; }
.status-toggle input[type="radio"] { display: none; }
.status-toggle label {
    display: flex; align-items: center; gap: 7px;
    padding: 8px 18px; border-radius: 30px;
    border: 1.5px solid #e2e8f0; cursor: pointer;
    font-size: 13px; font-weight: 600; color: #94a3b8;
    transition: all 0.2s; background: #f8fafc; user-select: none;
}
.status-toggle label .dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; flex-shrink: 0; }
.status-toggle input[type="radio"]:checked + label { border-color: transparent; color: white; }
.status-toggle #status_active:checked + label { background: #16a34a; box-shadow: 0 4px 12px rgba(22,163,74,0.3); }
.status-toggle #status_archive:checked + label { background: #64748b; box-shadow: 0 4px 12px rgba(100,116,139,0.3); }

/* ===== SECTION CARDS ===== */
.section-cards { display: flex; gap: 10px; flex-wrap: wrap; }
.section-card-item input[type="radio"] { display: none; }
.section-card-label {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 16px; border-radius: 10px;
    border: 1.5px solid #e2e8f0; cursor: pointer;
    transition: all 0.2s; background: #f8fafc;
    font-size: 13px; font-weight: 600; color: #64748b; user-select: none;
}
.section-card-label .sc-icon {
    width: 28px; height: 28px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; transition: all 0.2s;
}
.section-card-label:hover { border-color: #16a34a; background: #f0fdf4; }
.section-card-item input[type="radio"]:checked + .section-card-label { border-color: #16a34a; background: #f0fdf4; color: #15803d; }

/* ===== LANG TABS ===== */
.lang-tabs-wrap {
    display: flex; gap: 4px;
    background: #f1f5f9; padding: 4px;
    border-radius: 10px; width: fit-content;
    margin-bottom: 16px;
}
.lang-tab-btn {
    padding: 6px 18px; border-radius: 7px;
    font-size: 12px; font-weight: 700;
    color: #64748b; cursor: pointer;
    transition: all 0.2s; text-transform: uppercase;
    letter-spacing: 0.5px; border: none; background: transparent;
    user-select: none;
}
.lang-tab-btn.active { background: white; color: #0f172a; box-shadow: 0 1px 4px rgba(0,0,0,0.08); }
.lang-tab-btn:hover:not(.active) { color: #16a34a; }

.lang-pane { display: none; }
.lang-pane.active { display: block; }

/* ===== IMAGE UPLOAD ===== */
.img-upload-area {
    border: 2px dashed #e2e8f0; border-radius: 12px;
    padding: 28px 20px; text-align: center;
    cursor: pointer; transition: all 0.2s;
    background: #fafafa; position: relative;
}
.img-upload-area:hover, .img-upload-area.dragover { border-color: #16a34a; background: #f0fdf4; }
.img-upload-area input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer;
    width: 100%; height: 100%; margin: 0 !important;
}
.img-upload-icon {
    width: 52px; height: 52px; background: #dcfce7;
    border-radius: 12px; display: flex; align-items: center;
    justify-content: center; margin: 0 auto 14px;
    font-size: 22px; color: #16a34a;
}
.img-upload-title { font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
.img-upload-sub { font-size: 12px; color: #94a3b8; font-weight: 500; }
.img-upload-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background: #f0fdf4; border: 1px solid #bbf7d0;
    color: #16a34a; padding: 3px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 600; margin-top: 10px;
}
.img-preview-wrap { display: none; position: relative; border-radius: 10px; overflow: hidden; border: 1.5px solid #e2e8f0; }
.img-preview-wrap img { width: 100%; height: 180px; object-fit: cover; display: block; }
.img-preview-remove {
    position: absolute; top: 8px; right: 8px;
    width: 28px; height: 28px; background: rgba(0,0,0,0.5);
    border-radius: 50%; display: flex; align-items: center;
    justify-content: center; color: white; font-size: 11px;
    cursor: pointer; border: none; transition: background 0.2s;
}
.img-preview-remove:hover { background: rgba(239,68,68,0.8); }

/* ===== SAVE BTN ===== */
.btn-save-post {
    width: 100%; padding: 13px;
    background: linear-gradient(135deg, #16a34a, #22c55e);
    color: white; border: none; border-radius: 12px;
    font-size: 14px; font-weight: 700; font-family: 'Inter', sans-serif;
    cursor: pointer; transition: all 0.2s;
    box-shadow: 0 6px 20px rgba(22,163,74,0.3);
    display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-save-post:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(22,163,74,0.4); }
.btn-cancel {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    margin-top: 10px; padding: 9px; border-radius: 10px;
    background: #f8fafc; border: 1px solid #e2e8f0;
    font-size: 13px; font-weight: 600; color: #64748b;
    text-decoration: none; transition: all 0.2s;
}
.btn-cancel:hover { background: #f1f5f9; color: #475569; }

/* ===== CROP MODAL ===== */
#modal .modal-content { border-radius: 16px !important; border: none !important; box-shadow: 0 25px 50px rgba(0,0,0,0.15) !important; overflow: hidden; }
#modal .modal-header { background: #0f172a; border-bottom: none; padding: 16px 20px; }
#modal .modal-title { color: white; font-size: 15px; font-weight: 700; }
#modal .close { color: #94a3b8; opacity: 1; font-size: 20px; }
#modal .close:hover { color: white; }
#modal .modal-body { padding: 20px; background: #f8fafc; }
#modal .modal-footer { background: #fff; border-top: 1px solid #e2e8f0; padding: 12px 20px; gap: 8px; }
.preview { width: 100% !important; height: 160px; overflow: hidden; border-radius: 8px; border: 1.5px dashed #e2e8f0; background: #f1f5f9; }
.modal-lg { max-width: 960px !important; }
img { max-width: 100%; }

@media (max-width: 900px) { .post-form-wrap { grid-template-columns: 1fr; } }

/* Validation error states */
.form-section.has-error { border-color: #ef4444 !important; }
.form-control.is-invalid { border-color: #ef4444 !important; box-shadow: 0 0 0 3px rgba(239,68,68,0.1) !important; }
</style>
@endsection

@section('content')

<div class="post-page-header">
    <a href="javascript:history.back()" class="post-page-back">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="post-page-title">Yangi maqola qo'shish</h1>
        <div style="font-size:12px; color:#94a3b8; margin-top:2px; font-weight:500;">
            Kontent boshqaruvi › Yangi maqola
        </div>
    </div>
</div>

<form id="postCreateForm" method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
@csrf
<div class="post-form-wrap">

    {{-- ===== LEFT ===== --}}
    <div>

        {{-- Holat --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon" style="background:#dcfce7; color:#16a34a;"><i class="fas fa-toggle-on"></i></div>
                <h4 class="form-section-title">Holat</h4>
            </div>
            <div class="form-section-body" style="padding:14px 20px;">
                <div class="status-toggle">
                    <input type="radio" id="status_active" value="1" name="status" checked>
                    <label for="status_active"><span class="dot"></span> Faol</label>
                    <input type="radio" id="status_archive" value="2" name="status">
                    <label for="status_archive"><span class="dot"></span> Arxiv</label>
                </div>
            </div>
        </div>

        {{-- Bo'lim --}}
        <div class="form-section" id="sectionBox">
            <div class="form-section-header">
                <div class="form-section-icon" style="background:#e0f2fe; color:#0284c7;"><i class="fas fa-layer-group"></i></div>
                <h4 class="form-section-title">Bo'lim tanlash</h4>
            </div>
            <div class="form-section-body" style="padding:14px 20px;">
                <div id="sectionError" style="display:none; color:#dc2626; font-size:12px; font-weight:600; margin-bottom:10px;">
                    <i class="fas fa-exclamation-circle"></i> Bo'lim tanlanmagan — iltimos, bo'lim tanlang
                </div>
                <div class="section-cards">
                    @php
                        $sectionIcons = [
                            'Yangiliklar'        => ['icon'=>'fa-newspaper',     'color'=>'#16a34a','bg'=>'#dcfce7'],
                            "E'lonlar"           => ['icon'=>'fa-bullhorn',      'color'=>'#0284c7','bg'=>'#e0f2fe'],
                            'Kuzatuv kameralari' => ['icon'=>'fa-video',         'color'=>'#ea580c','bg'=>'#fff7ed'],
                            'Bizning xizmatlar'  => ['icon'=>'fa-briefcase',     'color'=>'#7c3aed','bg'=>'#ede9fe'],
                            "Sotuv bo'limi"      => ['icon'=>'fa-shopping-cart', 'color'=>'#0891b2','bg'=>'#ecfeff'],
                        ];
                    @endphp
                    @foreach($sections as $id => $entry)
                        @php $ico = $sectionIcons[$entry] ?? ['icon'=>'fa-briefcase','color'=>'#64748b','bg'=>'#f1f5f9']; @endphp
                        <div class="section-card-item">
                            <input class="section-checkboxes" type="radio" id="section_{{ $id }}" value="{{ $id }}" name="section_ids[]">
                            <label class="section-card-label" for="section_{{ $id }}">
                                <span class="sc-icon" style="background:{{ $ico['bg'] }};">
                                    <i class="fas {{ $ico['icon'] }}" style="color:{{ $ico['color'] }};"></i>
                                </span>
                                {{ $entry }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sarlavha --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon" style="background:#f3e8ff; color:#9333ea;"><i class="fas fa-heading"></i></div>
                <h4 class="form-section-title">Sarlavha</h4>
            </div>
            <div class="form-section-body">
                <div class="lang-tabs-wrap" id="titleTabs">
                    @foreach(config('app.locales') as $key => $val)
                        <button type="button" class="lang-tab-btn {{ $loop->first ? 'active' : '' }}"
                                data-target="title_pane_{{ $key }}">{{ strtoupper($val) }}</button>
                    @endforeach
                </div>
                @foreach(config('app.locales') as $key => $val)
                    <div class="lang-pane {{ $loop->first ? 'active' : '' }}" id="title_pane_{{ $key }}">
                        <input
                            class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                            type="text"
                            name="title_{{ $val }}"
                            id="title_{{ $val }}"
                            value="{{ old('title_'.$val, '') }}"
                            placeholder="Sarlavhani kiriting ({{ strtoupper($val) }})..."
                            autocomplete="off"
                        >
                        @if($errors->has('title'))
                            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                        @endif
                        <div class="title-error" style="display:none; color:#dc2626; font-size:12px; font-weight:600; margin-top:6px;">
                            <i class="fas fa-exclamation-circle"></i> Sarlavha kiritilmagan
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Asosiy matn --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon" style="background:#fef9c3; color:#ca8a04;"><i class="fas fa-align-left"></i></div>
                <h4 class="form-section-title">Asosiy matn</h4>
            </div>
            <div class="form-section-body">
                <div class="lang-tabs-wrap" id="contentTabs">
                    @foreach(config('app.locales') as $key => $val)
                        <button type="button" class="lang-tab-btn {{ $loop->first ? 'active' : '' }}"
                                data-target="content_pane_{{ $key }}">{{ strtoupper($val) }}</button>
                    @endforeach
                </div>
                @foreach(config('app.locales') as $key => $val)
                    <div class="lang-pane {{ $loop->first ? 'active' : '' }}" id="content_pane_{{ $key }}">
                        <div class="quill-wrap">
                            <div id="quill_{{ $val }}" data-lang="{{ $val }}"></div>
                        </div>
                        <textarea id="content_{{ $val }}" name="content_{{ $val }}" style="display:none;"></textarea>
                        @if($errors->has('content'))
                            <div class="text-danger" style="font-size:12px; margin-top:4px;">{{ $errors->first('content') }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ===== RIGHT ===== --}}
    <div>

        {{-- Saqlash --}}
        <div class="form-section">
            <div class="form-section-body" style="padding:16px;">
                <button id="sendPost" type="submit" class="btn-save-post">
                    <i class="fas fa-save"></i>
                    Saqlash
                </button>
                <a href="javascript:history.back()" class="btn-cancel">
                    <i class="fas fa-times" style="font-size:12px;"></i>
                    Bekor qilish
                </a>
            </div>
        </div>

        {{-- Rasm yuklash --}}
        <div class="form-section">
            <div class="form-section-header">
                <div class="form-section-icon" style="background:#dcfce7; color:#16a34a;"><i class="fas fa-image"></i></div>
                <h4 class="form-section-title">Asosiy rasm</h4>
            </div>
            <div class="form-section-body" style="padding:16px;">
                <div class="img-preview-wrap show-image-wrap" style="display:none; margin-bottom:12px;">
                    <img src="" class="show-image" alt="">
                    <button type="button" class="img-preview-remove" onclick="removeImage()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="img-upload-area" id="uploadArea">
                    <input type="file" name="image" class="image" accept="image/*" required>
                    <input type="hidden" name="image_base64">
                    <div class="img-upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="img-upload-title">Rasm yuklash</div>
                    <div class="img-upload-sub">Sudrab tashlang yoki bosib tanlang</div>
                    <div class="img-upload-badge">
                        <i class="fas fa-check-circle"></i> JPG, PNG, GIF — max 15 MB
                    </div>
                </div>
                <div style="margin-top:8px; font-size:11px; color:#94a3b8; text-align:center;">
                    <i class="fas fa-crop-alt" style="color:#16a34a;"></i>
                    Yuklangandan so'ng kesish oynasi ochiladi
                </div>
            </div>
        </div>

        {{-- Maslahatlar --}}
        <div class="form-section">
            <div class="form-section-body" style="padding:14px 16px;">
                <div style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:10px;">
                    <i class="fas fa-lightbulb" style="color:#ca8a04;"></i> Maslahatlar
                </div>
                <div style="display:flex; flex-direction:column; gap:8px;">
                    <div style="font-size:12px; color:#64748b; display:flex; gap:8px; align-items:start;">
                        <span style="color:#16a34a; font-size:10px; margin-top:3px; flex-shrink:0;">●</span>
                        Sarlavha qisqa va aniq bo'lsin — 60–90 belgi tavsiya etiladi
                    </div>
                    <div style="font-size:12px; color:#64748b; display:flex; gap:8px; align-items:start;">
                        <span style="color:#16a34a; font-size:10px; margin-top:3px; flex-shrink:0;">●</span>
                        Rasm 1920×1280 piksel o'lchamida bo'lishi maqbul
                    </div>
                    <div style="font-size:12px; color:#64748b; display:flex; gap:8px; align-items:start;">
                        <span style="color:#16a34a; font-size:10px; margin-top:3px; flex-shrink:0;">●</span>
                        Har bir tilda matnni alohida to'ldiring
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</form>

{{-- Crop modal --}}
<div class="modal fade" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-crop-alt mr-2"></i>Rasmni kesib oling</h5>
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">Ko'rinish</div>
                        <div class="preview"></div>
                        <div style="margin-top:10px; font-size:11px; color:#94a3b8;">Rasmning o'lchamini qirqichni sudrab sozlang</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal"
                        style="background:#f1f5f9; color:#64748b; border:none; border-radius:8px; padding:8px 16px; font-weight:600; font-size:13px; cursor:pointer;">
                    <i class="fas fa-times mr-1"></i> Bekor qilish
                </button>
                <button type="button" id="crop"
                        style="background:#16a34a; color:white; border:none; border-radius:8px; padding:8px 18px; font-weight:700; font-size:13px; cursor:pointer; box-shadow:0 4px 12px rgba(22,163,74,0.3);">
                    <i class="fas fa-crop-alt mr-1"></i> Kesib saqlash
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
{{-- Cropper --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
{{-- Quill editor --}}
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
// ========================================================
// QUILL EDITORS
// ========================================================
var quillInstances = {};

var quillToolbar = [
    [{ 'header': [1, 2, 3, 4, false] }],
    [{ 'font': [] }, { 'size': ['small', false, 'large', 'huge'] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ 'color': [] }, { 'background': [] }],
    ['blockquote', 'code-block'],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    [{ 'script': 'sub' }, { 'script': 'super' }],
    [{ 'indent': '-1' }, { 'indent': '+1' }],
    [{ 'align': [] }],
    ['link', 'image'],
    ['clean']
];

function initQuillEditor(lang) {
    if (quillInstances[lang]) return;
    var el = document.getElementById('quill_' + lang);
    if (!el) return;
    var q = new Quill(el, {
        theme: 'snow',
        placeholder: 'Maqola matnini yozing...',
        modules: { toolbar: quillToolbar }
    });
    quillInstances[lang] = q;
}

$(function () {

    // Init Quill for all visible (active) content panes on load
    $('[id^="content_pane_"]').each(function () {
        if ($(this).hasClass('active')) {
            var lang = $(this).find('[data-lang]').data('lang');
            if (lang) initQuillEditor(lang);
        }
    });

    // ========================================================
    // CUSTOM TAB SWITCHER
    // ========================================================
    function initTabGroup(groupEl) {
        var $group = $(groupEl);
        // Collect sibling pane IDs from this tab group's buttons
        var paneIds = $group.find('.lang-tab-btn').map(function () {
            return $(this).data('target');
        }).get();

        $group.find('.lang-tab-btn').on('click', function () {
            var target = $(this).data('target');
            $group.find('.lang-tab-btn').removeClass('active');
            $(this).addClass('active');
            // Hide only panes belonging to this group
            $.each(paneIds, function (i, id) { $('#' + id).removeClass('active').hide(); });
            $('#' + target).addClass('active').show();
            // Init Quill for content panes when shown
            var $quillEl = $('#' + target).find('[data-lang]');
            if ($quillEl.length) initQuillEditor($quillEl.data('lang'));
        });
    }
    initTabGroup('#titleTabs');
    initTabGroup('#contentTabs');

    // Show active panes on load
    $('.lang-pane.active').show();
    $('.lang-pane:not(.active)').hide();

    // Section change — clear error on select
    $('.section-checkboxes').on('change', function () {
        if ($('.section-checkboxes:checked').length > 0) {
            $('#sectionError').hide();
            $('#sectionBox').css('border-color', '#e2e8f0');
        }
    });

    // Title input — clear error on type
    $('[id^="title_"]').on('input', function () {
        $(this).closest('.lang-pane').find('.title-error').hide();
        $(this).removeClass('is-invalid');
    });

    // ========================================================
    // UPLOAD AREA — drag feedback
    // ========================================================
    var uploadArea = document.getElementById('uploadArea');
    if (uploadArea) {
        uploadArea.addEventListener('dragover',  function(e) { e.preventDefault(); this.classList.add('dragover'); });
        uploadArea.addEventListener('dragleave', function()  { this.classList.remove('dragover'); });
        uploadArea.addEventListener('drop',      function()  { this.classList.remove('dragover'); });
    }

    // ========================================================
    // FORM SUBMIT — validate + copy Quill HTML to textareas
    // ========================================================
    $('#postCreateForm').on('submit', function (e) {
        e.preventDefault();

        var valid = true;

        // 1. Check section selected
        if ($('.section-checkboxes:checked').length === 0) {
            $('#sectionError').show();
            $('#sectionBox').css('border-color', '#ef4444');
            $('#sectionBox')[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            valid = false;
        } else {
            $('#sectionError').hide();
            $('#sectionBox').css('border-color', '#e2e8f0');
        }

        // 2. Check UZ title (first locale = required)
        @php
            $firstLocaleVals = array_values(config('app.locales'));
            $firstLocaleKeys = array_keys(config('app.locales'));
            $firstVal = $firstLocaleVals[0];
            $firstKey = $firstLocaleKeys[0];
        @endphp
        var uzTitle = $('#title_{{ $firstVal }}').val().trim();
        if (!uzTitle) {
            // Switch to UZ tab and show error
            $('#titleTabs .lang-tab-btn:first').trigger('click');
            $('.title-error').hide();
            $('#title_pane_{{ $firstKey }} .title-error').show();
            $('#title_{{ $firstVal }}').addClass('is-invalid').focus();
            if (valid) {
                $('#title_{{ $firstVal }}')[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            valid = false;
        }

        if (!valid) return;

        // 3. Copy Quill HTML to hidden textareas
        $('[data-lang]').each(function () {
            var lang = $(this).data('lang');
            if (lang) initQuillEditor(lang);
        });
        $.each(quillInstances, function (lang, q) {
            var html = q.root.innerHTML;
            if (html === '<p><br></p>') html = '';
            $('#content_' + lang).val(html);
        });

        this.submit();
    });
});

// ========================================================
// IMAGE CROPPER
// ========================================================
var $modal = $('#modal');
var image  = document.getElementById('image');
var cropper;

$('body').on('change', '.image', function (e) {
    var files = e.target.files;
    if (!files || !files.length) return;
    var file = files[0];
    var done = function (url) { image.src = url; $modal.modal('show'); };
    if (URL)       { done(URL.createObjectURL(file)); }
    else if (FileReader) {
        var reader = new FileReader();
        reader.onload = function () { done(reader.result); };
        reader.readAsDataURL(file);
    }
});

$modal.on('shown.bs.modal', function () {
    cropper = new Cropper(image, {
        aspectRatio: 1.7, viewMode: 3, preview: '.preview', center: true,
        data: { x: 160, y: 0, height: 1050, width: 1940 }
    });
}).on('hidden.bs.modal', function () {
    cropper.destroy(); cropper = null;
});

$('#crop').click(function () {
    var canvas = cropper.getCroppedCanvas({ width: 1920, height: 1280 });
    canvas.toBlob(function (blob) {
        var url = URL.createObjectURL(blob);
        $('.show-image-wrap').show();
        $('.show-image').attr('src', url);
        $('#uploadArea').hide();
        $modal.modal('hide');
    });
});

function removeImage() {
    $('.show-image-wrap').hide();
    $('.show-image').attr('src', '');
    $('#uploadArea').show();
    $('input[name="image_base64"]').val('');
}
</script>
@endsection
