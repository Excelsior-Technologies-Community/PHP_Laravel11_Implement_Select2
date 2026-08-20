@extends('layouts.customer')

@section('content')

<div class="container py-5">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="header-icon">
                    <i class="fa-solid fa-store"></i>
                </span>

                <h2 class="fw-bold mb-0">Our Products</h2>
            </div>

            <p class="text-muted mb-0">
                Discover our latest products and find exactly what you need.
            </p>
        </div>

        <div class="product-count">
            <i class="fa-solid fa-box me-1"></i>
            {{ $products->total() }} Products
        </div>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">

            <i class="fa-solid fa-circle-check me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- SEARCH / FILTER CARD --}}
    <div class="filter-card shadow-sm mb-4">

        <div class="filter-header">

            <div>
                <h5 class="fw-bold mb-1">
                    <i class="fa-solid fa-sliders me-2"></i>
                    Find Products
                </h5>

                <small class="text-muted">
                    Search products or filter them by tag
                </small>
            </div>

        </div>


        <div class="row g-3 mt-1">

            {{-- PRODUCT SEARCH --}}
            <div class="col-lg-6">

                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-magnifying-glass me-1"></i>
                    Search Products
                </label>

                <select
                    id="productSearch"
                    class="form-select"
                    style="width:100%;">
                </select>

                <small class="text-muted">
                    Start typing to search products.
                </small>

            </div>


            {{-- TAG FILTER --}}
            <div class="col-lg-6">

                <label class="form-label fw-semibold">
                    <i class="fa-solid fa-tags me-1"></i>
                    Filter by Tag
                </label>

                <select
                    id="tagFilter"
                    class="form-select"
                    style="width:100%;">
                </select>

                <small class="text-muted">
                    Search existing tags or create a new one.
                </small>

            </div>

        </div>

    </div>


    {{-- PRODUCTS --}}
    @if($products->isEmpty())

        <div class="empty-state shadow-sm">

            <div class="empty-icon">
                <i class="fa-solid fa-box-open"></i>
            </div>

            <h4 class="fw-bold mt-3">
                No Products Found
            </h4>

            <p class="text-muted mb-0">
                We couldn't find any products matching your selection.
            </p>

        </div>

    @else

        <div class="row g-4">

            @foreach($products as $product)

                <div class="col-sm-6 col-lg-4 col-xl-3">

                    <div class="product-card h-100">

                        {{-- IMAGE --}}
                        <div class="product-image-wrapper">

                            <a href="{{ route('customer.products.show', $product) }}">

                                @if(!empty($product->images))

                                    @php
                                        $firstImg = $product->images[0] ?? null;
                                    @endphp

                                    @if($firstImg)

                                        <img
                                            src="{{ asset($firstImg) }}"
                                            class="product-img"
                                            alt="{{ $product->name }}"
                                        >

                                    @else

                                        <div class="no-image">
                                            <i class="fa-solid fa-image"></i>
                                        </div>

                                    @endif

                                @elseif($product->image)

                                    <img
                                        src="{{ asset($product->image) }}"
                                        class="product-img"
                                        alt="{{ $product->name }}"
                                    >

                                @else

                                    <div class="no-image">
                                        <i class="fa-solid fa-image"></i>
                                    </div>

                                @endif

                            </a>


                            {{-- STATUS --}}
                            <div class="status-badge
                                {{ $product->status === 'active'
                                    ? 'status-active'
                                    : 'status-inactive' }}">

                                <span class="status-dot"></span>

                                {{ ucfirst($product->status) }}

                            </div>

                        </div>


                        {{-- PRODUCT BODY --}}
                        <div class="product-body">

                            {{-- PRODUCT NAME --}}
                            <h5 class="product-title">

                                <a
                                    href="{{ route('customer.products.show', $product) }}"
                                >
                                    {{ $product->name }}
                                </a>

                            </h5>


                            {{-- CATEGORY --}}
                            @if($product->category)

                                <div class="product-category">

                                    <i class="fa-solid fa-layer-group me-1"></i>

                                    {{ $product->category }}

                                </div>

                            @endif


                            {{-- DETAILS --}}
                            <p class="product-details">

                                {{ Str::limit($product->details, 75) }}

                            </p>


                            {{-- TAGS --}}
                            @if($product->tags->count())

                                <div class="product-tags">

                                    @foreach($product->tags->take(3) as $tag)

                                        <span class="product-tag">

                                            <i class="fa-solid fa-tag"></i>

                                            {{ $tag->tag_name }}

                                        </span>

                                    @endforeach

                                    @if($product->tags->count() > 3)

                                        <span class="product-tag more-tag">

                                            +{{ $product->tags->count() - 3 }}

                                        </span>

                                    @endif

                                </div>

                            @endif


                            {{-- PRICE + BUTTON --}}
                            <div class="product-footer">

                                <div>

                                    <small class="text-muted d-block">
                                        Price
                                    </small>

                                    <span class="product-price">
                                        ₹{{ number_format($product->price, 2) }}
                                    </span>

                                </div>

                                <a
                                    href="{{ route('customer.products.show', $product) }}"
                                    class="view-btn"
                                    title="View Product"
                                >
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- PAGINATION --}}
        @if($products->hasPages())

            <div class="pagination-wrapper mt-5">

                {{ $products->links('pagination::bootstrap-5') }}

            </div>

        @endif

    @endif

</div>


{{-- LIGHTBOX --}}
<div
    class="modal fade"
    id="lightboxModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content lightbox-content">

            <div class="modal-body p-0 position-relative text-center">

                <img
                    src=""
                    id="lightboxImage"
                    class="lightbox-image"
                    alt="Product Image"
                >

                <button
                    type="button"
                    class="lightbox-close"
                    data-bs-dismiss="modal"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>

            </div>

        </div>

    </div>

</div>

@endsection


{{-- ========================================================= --}}
{{-- STYLES --}}
{{-- ========================================================= --}}

@push('styles')

<link
    href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    rel="stylesheet"
/>

<link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    rel="stylesheet"
/>

<style>

    /* =========================
       PAGE
    ========================= */

    body {
        background: #f6f8fb;
    }


    /* =========================
       HEADER
    ========================= */

    .header-icon {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #0d6efd;
        color: #fff;
        font-size: 18px;
    }

    .product-count {
        background: #fff;
        padding: 9px 16px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        color: #495057;
        box-shadow: 0 3px 15px rgba(0,0,0,.06);
    }


    /* =========================
       FILTER CARD
    ========================= */

    .filter-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #edf0f4;
    }

    .filter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }


    /* =========================
       SELECT2
    ========================= */

    .select2-container {
        width: 100% !important;
    }

    .select2-container--default
    .select2-selection--single {

        height: 46px !important;

        border: 1px solid #dee2e6 !important;

        border-radius: 9px !important;

        padding: 8px 12px !important;

        background: #fff !important;
    }

    .select2-container--default
    .select2-selection--single
    .select2-selection__rendered {

        line-height: 28px !important;

        color: #212529 !important;
    }

    .select2-container--default
    .select2-selection--single
    .select2-selection__arrow {

        height: 44px !important;

        right: 8px !important;
    }

    .select2-container--default
    .select2-selection--multiple {

        min-height: 46px !important;

        border: 1px solid #dee2e6 !important;

        border-radius: 9px !important;
    }


    /* =========================
       SELECT2 DROPDOWN
    ========================= */

    .select2-dropdown {

        border: 1px solid #dee2e6 !important;

        border-radius: 10px !important;

        box-shadow: 0 8px 25px rgba(0,0,0,.12);

        overflow: hidden;
    }

    .select2-results__option {

        padding: 10px 14px !important;
    }

    .select2-results__option--highlighted {

        background: #0d6efd !important;
        color: #fff !important;
    }

    .select2-search--dropdown {

        padding: 10px !important;
    }

    .select2-search--dropdown
    .select2-search__field {

        border-radius: 7px;

        border: 1px solid #dee2e6;

        padding: 8px 10px;
    }


    /* =========================
       PRODUCT CARD
    ========================= */

    .product-card {

        background: #fff;

        border-radius: 16px;

        overflow: hidden;

        border: 1px solid #edf0f4;

        box-shadow: 0 4px 18px rgba(0,0,0,.05);

        transition: all .25s ease;

    }

    .product-card:hover {

        transform: translateY(-6px);

        box-shadow: 0 15px 35px rgba(0,0,0,.12);

        border-color: #e0e7ef;

    }


    /* =========================
       IMAGE
    ========================= */

    .product-image-wrapper {

        height: 220px;

        position: relative;

        overflow: hidden;

        background: #f1f3f5;

    }

    .product-img {

        width: 100%;

        height: 220px;

        object-fit: cover;

        transition: transform .4s ease;

    }

    .product-card:hover .product-img {

        transform: scale(1.05);

    }

    .no-image {

        width: 100%;

        height: 100%;

        display: flex;

        align-items: center;

        justify-content: center;

        color: #adb5bd;

        font-size: 45px;

    }


    /* =========================
       STATUS
    ========================= */

    .status-badge {

        position: absolute;

        top: 12px;

        right: 12px;

        padding: 6px 11px;

        border-radius: 20px;

        font-size: 12px;

        font-weight: 600;

        background: rgba(255,255,255,.95);

        box-shadow: 0 3px 10px rgba(0,0,0,.12);

    }

    .status-active {
        color: #198754;
    }

    .status-inactive {
        color: #6c757d;
    }

    .status-dot {

        display: inline-block;

        width: 7px;

        height: 7px;

        border-radius: 50%;

        margin-right: 4px;

        background: currentColor;
    }


    /* =========================
       BODY
    ========================= */

    .product-body {

        padding: 18px;

    }

    .product-title {

        margin-bottom: 6px;

        font-size: 17px;

        font-weight: 700;

        white-space: nowrap;

        overflow: hidden;

        text-overflow: ellipsis;

    }

    .product-title a {

        color: #212529;

        text-decoration: none;

        transition: color .2s;

    }

    .product-title a:hover {

        color: #0d6efd;

    }


    /* =========================
       CATEGORY
    ========================= */

    .product-category {

        font-size: 12px;

        color: #6c757d;

        margin-bottom: 8px;

    }


    /* =========================
       DETAILS
    ========================= */

    .product-details {

        color: #6c757d;

        font-size: 13px;

        line-height: 1.5;

        min-height: 40px;

        margin-bottom: 12px;

    }


    /* =========================
       TAGS
    ========================= */

    .product-tags {

        display: flex;

        flex-wrap: wrap;

        gap: 5px;

        margin-bottom: 16px;

    }

    .product-tag {

        display: inline-flex;

        align-items: center;

        gap: 4px;

        background: #f1f5f9;

        color: #495057;

        border: 1px solid #e2e8f0;

        border-radius: 20px;

        padding: 4px 8px;

        font-size: 11px;

        font-weight: 500;

    }

    .product-tag i {

        font-size: 9px;

    }

    .more-tag {

        background: #e9ecef;

    }


    /* =========================
       FOOTER
    ========================= */

    .product-footer {

        display: flex;

        align-items: center;

        justify-content: space-between;

        border-top: 1px solid #f0f2f5;

        padding-top: 13px;

    }

    .product-price {

        font-size: 19px;

        font-weight: 800;

        color: #198754;

    }

    .view-btn {

        width: 38px;

        height: 38px;

        border-radius: 50%;

        display: flex;

        align-items: center;

        justify-content: center;

        background: #0d6efd;

        color: #fff;

        text-decoration: none;

        transition: all .2s ease;

    }

    .view-btn:hover {

        background: #0b5ed7;

        color: #fff;

        transform: translateX(3px);

    }


    /* =========================
       EMPTY STATE
    ========================= */

    .empty-state {

        background: #fff;

        border-radius: 16px;

        padding: 70px 20px;

        text-align: center;

        border: 1px solid #edf0f4;

    }

    .empty-icon {

        width: 80px;

        height: 80px;

        margin: auto;

        border-radius: 50%;

        display: flex;

        align-items: center;

        justify-content: center;

        background: #f1f3f5;

        color: #adb5bd;

        font-size: 32px;

    }


    /* =========================
       PAGINATION
    ========================= */

    .pagination-wrapper {

        display: flex;

        justify-content: center;

    }

    .pagination {

        gap: 5px;

    }

    .pagination .page-link {

        border-radius: 8px !important;

        border: 1px solid #dee2e6;

        color: #495057;

        padding: 8px 13px;

    }

    .pagination .page-item.active .page-link {

        background: #0d6efd;

        border-color: #0d6efd;

    }


    /* =========================
       LIGHTBOX
    ========================= */

    .lightbox-content {

        background: transparent;

        border: 0;

    }

    .lightbox-image {

        max-width: 100%;

        max-height: 85vh;

        border-radius: 12px;

    }

    .lightbox-close {

        position: absolute;

        top: 10px;

        right: 10px;

        width: 38px;

        height: 38px;

        border: 0;

        border-radius: 50%;

        background: #fff;

        color: #212529;

        box-shadow: 0 3px 12px rgba(0,0,0,.2);

    }


    /* =========================
       MOBILE
    ========================= */

    @media (max-width: 575px) {

        .container {
            padding-left: 15px;
            padding-right: 15px;
        }

        .filter-card {
            padding: 18px;
        }

        .product-image-wrapper,
        .product-img {
            height: 210px;
        }

    }

</style>

@endpush


{{-- ========================================================= --}}
{{-- SCRIPTS --}}
{{-- ========================================================= --}}

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>


<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | PRODUCT SEARCH
    |--------------------------------------------------------------------------
    */

    $('#productSearch').select2({

        placeholder: 'Search products...',

        allowClear: true,

        width: '100%',

        minimumInputLength: 1,

        ajax: {

            url: '{{ url("/api/products/select2") }}',

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

        }

    });


    /*
    |--------------------------------------------------------------------------
    | PRODUCT SELECTED
    |--------------------------------------------------------------------------
    */

    $('#productSearch').on('select2:select', function (e) {

        const productId = e.params.data.id;

        window.location.href =
            '{{ url("/customer/products") }}/' + productId;

    });


    /*
    |--------------------------------------------------------------------------
    | AJAX TAG FILTER
    |--------------------------------------------------------------------------
    */

    $('#tagFilter').select2({

        placeholder: 'Search or create a tag...',

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

        tags: true,

        createTag: function (params) {

            const term = $.trim(params.term);

            if (term === '') {
                return null;
            }

            return {

                id: 'new:' + term,

                text: 'Create "' + term + '"',

                newTag: true

            };

        }

    });


    /*
    |--------------------------------------------------------------------------
    | TAG SELECTED
    |--------------------------------------------------------------------------
    */

    $('#tagFilter').on('select2:select', function (e) {

        const data = e.params.data;


        /*
        | Existing tag
        */

        if (!data.newTag) {

            window.location.href =
                '{{ url("/customer/products") }}?tag=' + data.id;

            return;

        }


        /*
        | New tag
        */

        const tagName = data.id.replace('new:', '');


        $.ajax({

            url: '{{ url("/api/tags") }}',

            method: 'POST',

            data: {

                tag_name: tagName,

                _token: '{{ csrf_token() }}'

            },

            success: function (response) {

                if (response.success) {

                    const newTag = new Option(

                        response.data.text,

                        response.data.id,

                        true,

                        true

                    );


                    $('#tagFilter')

                        .append(newTag)

                        .trigger('change');


                    window.location.href =
                        '{{ url("/customer/products") }}?tag=' +
                        response.data.id;

                }

            },

            error: function (xhr) {

                let message = 'Unable to create tag.';


                if (
                    xhr.responseJSON &&
                    xhr.responseJSON.errors &&
                    xhr.responseJSON.errors.tag_name
                ) {

                    message =
                        xhr.responseJSON.errors.tag_name[0];

                }


                alert(message);


                $('#tagFilter')

                    .val(null)

                    .trigger('change');

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | LIGHTBOX
    |--------------------------------------------------------------------------
    */

    $('.product-img').css('cursor', 'pointer');


    $('.product-img').on('click', function (e) {

        e.preventDefault();

        const imageUrl = $(this).attr('src');

        $('#lightboxImage').attr('src', imageUrl);

        const modal =
            new bootstrap.Modal(
                document.getElementById('lightboxModal')
            );

        modal.show();

    });


});

</script>

@endpush