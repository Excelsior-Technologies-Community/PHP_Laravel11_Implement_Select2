@extends('layouts.admin')

@section('content')
<div class="container">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fa-solid fa-box-open me-2 text-primary"></i>
                Create Product
            </h2>
            <p class="text-muted mb-0">Add a new product to your store.</p>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fa-solid fa-circle-check me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <div class="fw-bold mb-2">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                Please fix the following errors:
            </div>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Card --}}
    <div class="card border-0 shadow-sm create-product-card">

        <div class="card-header bg-white border-0 p-4">
            <h5 class="fw-bold mb-1">
                Product Information
            </h5>

            <small class="text-muted">
                Enter the product details below.
            </small>
        </div>

        <div class="card-body p-4">

            <form action="{{ route('products.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="productForm">

                @csrf

                {{-- Product Name + Category --}}
                <div class="row">

                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">
                            Product Name
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fa-solid fa-box"></i>
                            </span>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   placeholder="Enter product name"
                                   value="{{ old('name') }}"
                                   required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">
                            Category
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fa-solid fa-layer-group"></i>
                            </span>

                            <input type="text"
                                   name="category"
                                   class="form-control"
                                   placeholder="Enter category"
                                   list="categoryList"
                                   value="{{ old('category') }}"
                                   required>
                        </div>

                        <datalist id="categoryList"></datalist>
                    </div>

                </div>


                {{-- Details --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Product Details
                        <span class="text-danger">*</span>
                    </label>

                    <textarea name="details"
                              class="form-control"
                              rows="4"
                              placeholder="Enter product details..."
                              required>{{ old('details') }}</textarea>

                </div>


                {{-- Size / Color / Price --}}
                <div class="row">

                    <div class="col-md-4 mb-4">

                        <label class="form-label fw-semibold">
                            Size
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fa-solid fa-ruler"></i>
                            </span>

                            <input type="text"
                                   name="size"
                                   class="form-control"
                                   placeholder="e.g. M, L, XL"
                                   value="{{ old('size') }}"
                                   required>
                        </div>

                    </div>


                    <div class="col-md-4 mb-4">

                        <label class="form-label fw-semibold">
                            Color
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fa-solid fa-palette"></i>
                            </span>

                            <input type="text"
                                   name="color"
                                   class="form-control"
                                   placeholder="e.g. Black"
                                   value="{{ old('color') }}"
                                   required>
                        </div>

                    </div>


                    <div class="col-md-4 mb-4">

                        <label class="form-label fw-semibold">
                            Price (₹)
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light">
                                ₹
                            </span>

                            <input type="number"
                                   name="price"
                                   class="form-control"
                                   placeholder="0.00"
                                   step="0.01"
                                   min="0"
                                   value="{{ old('price') }}"
                                   required>

                        </div>

                    </div>

                </div>


                {{-- Tags + Status --}}
                <div class="row">

                    {{-- AJAX Tags --}}
                    <div class="col-md-8 mb-4">

                        <label class="form-label fw-semibold">
                            Product Tags
                        </label>

                        <select name="tag_ids[]"
                                id="tagSelect"
                                class="form-select"
                                multiple>
                        </select>

                        <div class="form-text">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            Type to search tags. You can create a new tag if it does not exist.
                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4 mb-4">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="active"
                                {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>


                {{-- Image Upload --}}
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Product Gallery Images
                    </label>

                    <div class="upload-box">

                        <i class="fa-solid fa-cloud-arrow-up fa-2x text-primary mb-2"></i>

                        <input type="file"
                               name="images[]"
                               id="productImages"
                               class="form-control"
                               accept="image/*"
                               multiple>

                        <div class="form-text">
                            Select multiple images. Maximum 2MB per image.
                        </div>

                    </div>

                </div>


                {{-- Image Preview --}}
                <div id="imagePreviewContainer"
                     class="image-preview-container mb-4">
                </div>


                {{-- Buttons --}}
                <div class="border-top pt-4 mt-4">

                    <div class="d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary px-4">

                            <i class="fa-solid fa-check me-1"></i>
                            Create Product

                        </button>

                        <a href="{{ route('products.index') }}"
                           class="btn btn-light border px-4">

                            Cancel

                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>

</div>
@endsection


@push('styles')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
      rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      rel="stylesheet">

<style>

    body {
        background-color: #f5f7fb;
    }

    .create-product-card {
        border-radius: 14px;
        overflow: visible;
    }

    .form-label {
        color: #344054;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border-color: #d0d5dd;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .2rem rgba(13,110,253,.1);
    }

    .input-group-text {
        border-color: #d0d5dd;
        color: #667085;
    }

    textarea.form-control {
        min-height: 110px;
    }


    /* ==========================================
       SELECT2
    ========================================== */

    .select2-container {
        width: 100% !important;
    }

    .select2-container--default
    .select2-selection--multiple {

        min-height: 44px;

        border: 1px solid #d0d5dd;

        border-radius: 6px;

        padding: 4px 8px;

        background-color: #fff;

    }

    .select2-container--default
    .select2-selection--multiple:focus,
    .select2-container--default.select2-container--focus
    .select2-selection--multiple {

        border-color: #86b7fe;

        box-shadow: 0 0 0 .2rem rgba(13,110,253,.1);

    }

    .select2-container--default
    .select2-selection--multiple
    .select2-selection__choice {

        background-color: #e7f0ff;

        border: 1px solid #cfe0ff;

        color: #0d6efd;

        border-radius: 5px;

        padding: 3px 8px;

    }

    .select2-container--default
    .select2-search--inline
    .select2-search__field {

        margin-top: 5px;

        min-height: 28px;

    }

    .select2-dropdown {

        border: 1px solid #d0d5dd;

        box-shadow: 0 8px 25px rgba(0,0,0,.08);

    }

    .select2-results__option {
        padding: 10px 12px;
    }

    .select2-results__option--highlighted {
        background-color: #0d6efd !important;
    }


    /* ==========================================
       CREATE TAG BUTTON
    ========================================== */

    .create-tag-option {

        display: flex;

        align-items: center;

        justify-content: space-between;

        padding: 8px 10px;

        background: #f8f9fa;

        border-top: 1px solid #eee;

    }

    .create-tag-option button {

        border: 0;

        background: #0d6efd;

        color: white;

        border-radius: 5px;

        padding: 5px 10px;

        font-size: 13px;

        cursor: pointer;

    }

    .create-tag-option button:hover {
        background: #0b5ed7;
    }


    /* ==========================================
       IMAGE UPLOAD
    ========================================== */

    .upload-box {

        background: #f8f9fa;

        border: 1px dashed #b8c0cc;

        border-radius: 10px;

        padding: 20px;

        text-align: center;

    }

    .upload-box .form-control {
        background: white;
    }


    /* ==========================================
       IMAGE PREVIEW
    ========================================== */

    .image-preview-container {

        display: flex;

        flex-wrap: wrap;

        gap: 12px;

    }

    .preview-item {

        position: relative;

    }

    .preview-item img {

        width: 100px;

        height: 100px;

        object-fit: cover;

        border-radius: 10px;

        border: 2px solid #dee2e6;

        padding: 2px;

        background: white;

    }

</style>

@endpush


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>


<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | AJAX TAG SEARCH
    |--------------------------------------------------------------------------
    */

    $('#tagSelect').select2({

        placeholder: 'Search or select product tags...',

        allowClear: true,

        width: '100%',

        minimumInputLength: 1,

        ajax: {

            url: '{{ url("/api/tags/select2") }}',

            dataType: 'json',

            delay: 250,

            data: function (params) {

                return {
                    q: params.term || ''
                };

            },

            processResults: function (data) {

                return {
                    results: data
                };

            },

            cache: true

        },

        language: {

            inputTooShort: function () {
                return 'Type at least 1 character to search tags.';
            },

            searching: function () {
                return 'Searching tags...';
            },

            noResults: function () {
                return 'No matching tags found.';
            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | DYNAMIC CREATE TAG
    |--------------------------------------------------------------------------
    */

    $('#tagSelect').on('select2:open', function () {

        const searchBox = $('.select2-search__field');

        searchBox.off('input.createTag');

        searchBox.on('input.createTag', function () {

            const term = $(this).val().trim();

            $('.create-tag-option').remove();

            if (!term) {
                return;
            }

            const createOption = $(
                '<div class="create-tag-option">' +

                    '<span>' +
                        '<i class="fa-solid fa-plus me-1"></i>' +
                        'Create "' + escapeHtml(term) + '"' +
                    '</span>' +

                    '<button type="button" class="create-tag-btn">' +
                        'Create' +
                    '</button>' +

                '</div>'
            );

            $('.select2-dropdown').append(createOption);

            createOption.find('.create-tag-btn').on('click', function (e) {

                e.preventDefault();

                createTag(term);

            });

        });

    });


    /*
    |--------------------------------------------------------------------------
    | IMAGE PREVIEW
    |--------------------------------------------------------------------------
    */

    $('#productImages').on('change', function () {

        $('#imagePreviewContainer').empty();

        const files = this.files;

        for (let i = 0; i < files.length; i++) {

            const reader = new FileReader();

            reader.onload = function (e) {

                $('#imagePreviewContainer').append(

                    '<div class="preview-item">' +

                        '<img src="' + e.target.result + '" alt="Preview">' +

                    '</div>'

                );

            };

            reader.readAsDataURL(files[i]);

        }

    });


    /*
    |--------------------------------------------------------------------------
    | CATEGORY AUTOCOMPLETE
    |--------------------------------------------------------------------------
    */

    loadCategories();

});


/*
|--------------------------------------------------------------------------
| CREATE TAG AJAX
|--------------------------------------------------------------------------
*/

function createTag(tagName) {

    $.ajax({

        url: '{{ url("/api/tags") }}',

        method: 'POST',

        data: {

            tag_name: tagName,

            _token: '{{ csrf_token() }}'

        },

        beforeSend: function () {

            $('.create-tag-btn')
                .prop('disabled', true)
                .text('Creating...');

        },

        success: function (response) {

            /*
             * Add the newly-created tag to Select2
             */

            const newOption = new Option(
                response.tag_name,
                response.id,
                true,
                true
            );

            $('#tagSelect')
                .append(newOption)
                .trigger('change');


            /*
             * Close dropdown
             */

            $('#tagSelect').select2('close');


            /*
             * Success message
             */

            showToast(
                'success',
                'Tag "' + response.tag_name + '" created successfully.'
            );

        },

        error: function (xhr) {

            let message = 'Unable to create tag.';

            if (
                xhr.responseJSON &&
                xhr.responseJSON.message
            ) {

                message = xhr.responseJSON.message;

            }

            showToast('error', message);

        },

        complete: function () {

            $('.create-tag-btn')
                .prop('disabled', false)
                .text('Create');

        }

    });

}


/*
|--------------------------------------------------------------------------
| CATEGORY LOADING
|--------------------------------------------------------------------------
*/

function loadCategories() {

    const categories = @json(
        \App\Models\Product::whereNotNull('category')
            ->distinct()
            ->pluck('category')
    );

    const datalist = document.getElementById('categoryList');

    categories.forEach(function (category) {

        const option = document.createElement('option');

        option.value = category;

        datalist.appendChild(option);

    });

}


/*
|--------------------------------------------------------------------------
| HTML ESCAPE
|--------------------------------------------------------------------------
*/

function escapeHtml(text) {

    return $('<div>')
        .text(text)
        .html();

}


/*
|--------------------------------------------------------------------------
| TOAST
|--------------------------------------------------------------------------
*/

function showToast(type, message) {

    const alertClass =
        type === 'success'
            ? 'alert-success'
            : 'alert-danger';

    const icon =
        type === 'success'
            ? 'fa-circle-check'
            : 'fa-circle-exclamation';

    const toast = $(

        '<div class="alert ' + alertClass +
        ' position-fixed shadow-lg" ' +
        'style="top:20px;right:20px;z-index:9999;">' +

            '<i class="fa-solid ' + icon + ' me-2"></i>' +

            escapeHtml(message) +

            '<button type="button" class="btn-close ms-3"></button>' +

        '</div>'

    );

    $('body').append(toast);

    toast.find('.btn-close').on('click', function () {
        toast.remove();
    });

    setTimeout(function () {

        toast.fadeOut(300, function () {
            $(this).remove();
        });

    }, 3000);

}

</script>

@endpush