@extends('layouts.admin')

@section('content')

<div class="container-fluid py-4">

    {{-- ============================================================
        PAGE HEADER
    ============================================================ --}}
    <div class="page-header mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="page-icon">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>

                <div>
                    <h2 class="fw-bold mb-0">Products</h2>

                    <p class="text-muted mb-0">
                        Manage your products, tags and availability
                    </p>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">

            <button
                type="button"
                class="btn btn-outline-primary px-3"
                data-bs-toggle="modal"
                data-bs-target="#bulkActionsModal"
            >
                <i class="fa-solid fa-layer-group me-1"></i>
                Bulk Actions
            </button>

            <a
                href="{{ route('products.create') }}"
                class="btn btn-primary px-3"
            >
                <i class="fa-solid fa-plus me-1"></i>
                Add Product
            </a>

        </div>

    </div>


    {{-- ============================================================
        SUCCESS MESSAGE
    ============================================================ --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4">

            <div class="d-flex align-items-center">

                <div class="alert-icon success-icon">
                    <i class="fa-solid fa-check"></i>
                </div>

                <div>
                    <strong>Success!</strong>
                    <div>{{ session('success') }}</div>
                </div>

            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ============================================================
        ERROR MESSAGE
    ============================================================ --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4">

            <div class="d-flex align-items-center">

                <div class="alert-icon danger-icon">
                    <i class="fa-solid fa-exclamation"></i>
                </div>

                <div>
                    <strong>Error!</strong>
                    <div>{{ session('error') }}</div>
                </div>

            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ============================================================
        PRODUCT SUMMARY
    ============================================================ --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="summary-card">

                <div class="summary-icon primary-icon">
                    <i class="fa-solid fa-box"></i>
                </div>

                <div>
                    <div class="summary-label">
                        Total Products
                    </div>

                    <div class="summary-value">
                        {{ $products->total() }}
                    </div>
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="summary-card">

                <div class="summary-icon success-icon-bg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>

                <div>
                    <div class="summary-label">
                        Active Products
                    </div>

                    <div class="summary-value">
                        {{ \App\Models\Product::where('status', 'active')->count() }}
                    </div>
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="summary-card">

                <div class="summary-icon warning-icon-bg">
                    <i class="fa-solid fa-tags"></i>
                </div>

                <div>
                    <div class="summary-label">
                        Tags
                    </div>

                    <div class="summary-value">
                        {{ \App\Models\Tag::count() }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        PRODUCT TABLE CARD
    ============================================================ --}}
    <div class="card product-table-card border-0 shadow-sm">

        {{-- TABLE HEADER --}}
        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="fa-solid fa-list me-2 text-primary"></i>
                        Product List
                    </h5>

                    <small class="text-muted">
                        Showing {{ $products->firstItem() ?? 0 }}
                        -
                        {{ $products->lastItem() ?? 0 }}
                        of {{ $products->total() }} products
                    </small>
                </div>

                <div class="selected-count" id="selectedCount">
                    0 selected
                </div>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th class="checkbox-column">
                            <input
                                type="checkbox"
                                id="selectAll"
                                class="form-check-input"
                            >
                        </th>

                        <th>Product</th>

                        <th>Tags</th>

                        <th>Category</th>

                        <th>Price</th>

                        <th>Status</th>

                        <th class="text-center">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($products as $product)

                        <tr>

                            {{-- CHECKBOX --}}
                            <td>

                                <input
                                    type="checkbox"
                                    name="product_ids[]"
                                    value="{{ $product->id }}"
                                    class="form-check-input row-check"
                                >

                            </td>


                            {{-- PRODUCT --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    {{-- PRODUCT IMAGE --}}
                                    <div class="product-image-wrapper me-3">

                                        @if(!empty($product->images))

                                            @php
                                                $firstImage = $product->images[0] ?? null;
                                            @endphp

                                            @if($firstImage)

                                                <img
                                                    src="{{ asset($firstImage) }}"
                                                    class="product-image"
                                                    alt="{{ $product->name }}"
                                                    onclick="openLightbox('{{ asset($firstImage) }}')"
                                                >

                                            @else

                                                <div class="product-placeholder">
                                                    <i class="fa-solid fa-image"></i>
                                                </div>

                                            @endif

                                        @elseif($product->image)

                                            <img
                                                src="{{ asset($product->image) }}"
                                                class="product-image"
                                                alt="{{ $product->name }}"
                                                onclick="openLightbox('{{ asset($product->image) }}')"
                                            >

                                        @else

                                            <div class="product-placeholder">
                                                <i class="fa-solid fa-image"></i>
                                            </div>

                                        @endif

                                    </div>


                                    {{-- PRODUCT INFO --}}
                                    <div class="product-info">

                                        <a
                                            href="{{ route('products.show', $product) }}"
                                            class="product-name"
                                        >
                                            {{ $product->name }}
                                        </a>

                                        <div class="product-description">

                                            {{ Str::limit($product->details, 55) }}

                                        </div>

                                        <div class="product-meta">

                                            <span>
                                                <i class="fa-solid fa-hashtag"></i>
                                                {{ $product->id }}
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- TAGS --}}
                            <td>

                                <div class="tags-wrapper">

                                    @forelse($product->tags as $tag)

                                        <span class="product-tag">
                                            <i class="fa-solid fa-tag me-1"></i>
                                            {{ $tag->tag_name }}
                                        </span>

                                    @empty

                                        <span class="no-tags">
                                            No tags
                                        </span>

                                    @endforelse

                                </div>

                            </td>


                            {{-- CATEGORY --}}
                            <td>

                                @if($product->category)

                                    <span class="category-badge">

                                        <i class="fa-solid fa-folder me-1"></i>

                                        {{ $product->category }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- PRICE --}}
                            <td>

                                <div class="price">

                                    ₹{{ number_format($product->price, 2) }}

                                </div>

                            </td>


                            {{-- STATUS --}}
                            <td>

                                <form
                                    action="{{ route('products.toggleStatus', $product) }}"
                                    method="POST"
                                    class="status-form"
                                >

                                    @csrf

                                    @if($product->status === 'active')

                                        <button
                                            type="submit"
                                            class="status-badge status-active"
                                            title="Click to deactivate"
                                        >
                                            <span class="status-dot"></span>
                                            Active
                                        </button>

                                    @else

                                        <button
                                            type="submit"
                                            class="status-badge status-inactive"
                                            title="Click to activate"
                                        >
                                            <span class="status-dot"></span>
                                            Inactive
                                        </button>

                                    @endif

                                </form>

                            </td>


                            {{-- ACTIONS --}}
                            <td>

                                <div class="action-buttons">

                                    {{-- VIEW --}}
                                    <a
                                        href="{{ route('products.show', $product) }}"
                                        class="action-btn view-btn"
                                        title="View Product"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                    </a>


                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('products.edit', $product) }}"
                                        class="action-btn edit-btn"
                                        title="Edit Product"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                    </a>


                                    {{-- DELETE --}}
                                    <form
                                        action="{{ route('products.destroy', $product) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this product?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-btn delete-btn"
                                            title="Delete Product"
                                        >
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="empty-state"
                            >

                                <div class="empty-icon">
                                    <i class="fa-solid fa-box-open"></i>
                                </div>

                                <h5>
                                    No Products Found
                                </h5>

                                <p class="text-muted mb-3">
                                    You haven't added any products yet.
                                </p>

                                <a
                                    href="{{ route('products.create') }}"
                                    class="btn btn-primary"
                                >
                                    <i class="fa-solid fa-plus me-1"></i>
                                    Add Your First Product
                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($products->hasPages())

            <div class="card-footer bg-white border-0 p-4">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div class="text-muted small">

                        Showing
                        <strong>{{ $products->firstItem() ?? 0 }}</strong>
                        -
                        <strong>{{ $products->lastItem() ?? 0 }}</strong>
                        of
                        <strong>{{ $products->total() }}</strong>
                        products

                    </div>

                    <div>
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>

                </div>

            </div>

        @endif

    </div>

</div>


{{-- ============================================================
    BULK ACTIONS MODAL
============================================================ --}}
<div
    class="modal fade"
    id="bulkActionsModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bulk-modal-header">

                <div>

                    <h5 class="modal-title fw-bold">

                        <i class="fa-solid fa-layer-group me-2"></i>

                        Bulk Actions

                    </h5>

                    <small class="opacity-75">
                        Manage multiple products at once
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route('products.bulkDelete') }}"
                id="bulkDeleteForm"
            >

                @csrf

                <div class="modal-body p-4">

                    <div class="bulk-selected-box mb-4">

                        <i class="fa-solid fa-check-circle me-2"></i>

                        <strong id="bulkSelectedText">
                            0 products selected
                        </strong>

                    </div>


                    <input
                        type="hidden"
                        name="product_ids"
                        id="bulkProductIds"
                    >


                    <div class="d-grid gap-3">

                        {{-- DELETE --}}
                        <button
                            type="submit"
                            class="bulk-action delete-action"
                            onclick="return validateBulkDelete()"
                        >

                            <span class="bulk-action-icon">
                                <i class="fa-solid fa-trash"></i>
                            </span>

                            <span class="text-start">

                                <strong>
                                    Delete Selected
                                </strong>

                                <small>
                                    Move selected products to trash
                                </small>

                            </span>

                        </button>


                        {{-- DEACTIVATE --}}
                        <button
                            type="button"
                            class="bulk-action deactivate-action"
                            onclick="bulkStatus('inactive')"
                        >

                            <span class="bulk-action-icon">
                                <i class="fa-solid fa-pause"></i>
                            </span>

                            <span class="text-start">

                                <strong>
                                    Deactivate Selected
                                </strong>

                                <small>
                                    Hide selected products from customers
                                </small>

                            </span>

                        </button>


                        {{-- ACTIVATE --}}
                        <button
                            type="button"
                            class="bulk-action activate-action"
                            onclick="bulkStatus('active')"
                        >

                            <span class="bulk-action-icon">
                                <i class="fa-solid fa-play"></i>
                            </span>

                            <span class="text-start">

                                <strong>
                                    Activate Selected
                                </strong>

                                <small>
                                    Make selected products visible
                                </small>

                            </span>

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ============================================================
    LIGHTBOX
============================================================ --}}
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
                    class="img-fluid lightbox-image"
                    alt="Product image"
                >

                <button
                    type="button"
                    class="btn-close lightbox-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

        </div>

    </div>

</div>

@endsection


{{-- ============================================================
    STYLES
============================================================ --}}
@push('styles')

<link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    rel="stylesheet"
>

<style>

    /* ------------------------------------------------------------
       PAGE
    ------------------------------------------------------------ */

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .page-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e9f0ff;
        color: #0d6efd;
        font-size: 20px;
    }


    /* ------------------------------------------------------------
       SUMMARY CARDS
    ------------------------------------------------------------ */

    .summary-card {
        background: #fff;
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 3px 15px rgba(0,0,0,.05);
        border: 1px solid #edf0f4;
        transition: .2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,.08);
    }

    .summary-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .primary-icon {
        background: #e9f0ff;
        color: #0d6efd;
    }

    .success-icon-bg {
        background: #e8f8ef;
        color: #198754;
    }

    .warning-icon-bg {
        background: #fff5df;
        color: #f59f00;
    }

    .summary-label {
        color: #6c757d;
        font-size: 13px;
        margin-bottom: 2px;
    }

    .summary-value {
        font-size: 22px;
        font-weight: 700;
    }


    /* ------------------------------------------------------------
       TABLE
    ------------------------------------------------------------ */

    .product-table-card {
        border-radius: 16px;
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background: #f8f9fb;
        color: #495057;
        border-bottom: 1px solid #e9ecef;
        padding: 15px 16px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .4px;
        font-weight: 700;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 16px;
        border-color: #f0f1f3;
    }

    .table tbody tr {
        transition: background .15s ease;
    }

    .table tbody tr:hover {
        background: #fafcff;
    }

    .checkbox-column {
        width: 45px;
    }


    /* ------------------------------------------------------------
       PRODUCT IMAGE
    ------------------------------------------------------------ */

    .product-image-wrapper {
        width: 58px;
        height: 58px;
        flex-shrink: 0;
    }

    .product-image {
        width: 58px;
        height: 58px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        transition: .2s ease;
    }

    .product-image:hover {
        transform: scale(1.08);
        box-shadow: 0 5px 15px rgba(0,0,0,.15);
    }

    .product-placeholder {
        width: 58px;
        height: 58px;
        border-radius: 12px;
        background: #f1f3f5;
        color: #adb5bd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        border: 1px solid #e5e7eb;
    }


    /* ------------------------------------------------------------
       PRODUCT INFORMATION
    ------------------------------------------------------------ */

    .product-name {
        color: #212529;
        font-weight: 700;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 3px;
    }

    .product-name:hover {
        color: #0d6efd;
    }

    .product-description {
        font-size: 12px;
        color: #6c757d;
        max-width: 250px;
    }

    .product-meta {
        font-size: 11px;
        color: #adb5bd;
        margin-top: 4px;
    }


    /* ------------------------------------------------------------
       TAGS
    ------------------------------------------------------------ */

    .tags-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        max-width: 240px;
    }

    .product-tag {
        display: inline-flex;
        align-items: center;
        background: #eef6ff;
        color: #0d6efd;
        border: 1px solid #d7e9ff;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .no-tags {
        color: #adb5bd;
        font-size: 12px;
    }


    /* ------------------------------------------------------------
       CATEGORY
    ------------------------------------------------------------ */

    .category-badge {
        display: inline-flex;
        align-items: center;
        background: #f4f5f7;
        color: #495057;
        border: 1px solid #e2e5e9;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }


    /* ------------------------------------------------------------
       PRICE
    ------------------------------------------------------------ */

    .price {
        color: #198754;
        font-weight: 700;
        white-space: nowrap;
    }


    /* ------------------------------------------------------------
       STATUS
    ------------------------------------------------------------ */

    .status-form {
        display: inline-block;
    }

    .status-badge {
        border: none;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: .2s ease;
    }

    .status-badge:hover {
        transform: translateY(-1px);
    }

    .status-active {
        background: #e8f8ef;
        color: #198754;
    }

    .status-inactive {
        background: #fff0f0;
        color: #dc3545;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }


    /* ------------------------------------------------------------
       ACTION BUTTONS
    ------------------------------------------------------------ */

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 5px;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid;
        background: transparent;
        transition: .2s ease;
        text-decoration: none;
        cursor: pointer;
    }

    .view-btn {
        color: #0dcaf0;
        border-color: #b9eaf3;
    }

    .view-btn:hover {
        background: #0dcaf0;
        color: white;
    }

    .edit-btn {
        color: #f59f00;
        border-color: #ffe0a3;
    }

    .edit-btn:hover {
        background: #f59f00;
        color: white;
    }

    .delete-btn {
        color: #dc3545;
        border-color: #f2b8be;
    }

    .delete-btn:hover {
        background: #dc3545;
        color: white;
    }


    /* ------------------------------------------------------------
       SELECTED COUNT
    ------------------------------------------------------------ */

    .selected-count {
        background: #f1f5ff;
        color: #0d6efd;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
    }


    /* ------------------------------------------------------------
       EMPTY STATE
    ------------------------------------------------------------ */

    .empty-state {
        padding: 70px 20px !important;
        text-align: center;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #f1f3f5;
        color: #adb5bd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin: 0 auto 15px;
    }


    /* ------------------------------------------------------------
       ALERTS
    ------------------------------------------------------------ */

    .alert-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }

    .success-icon {
        background: #d1f3df;
        color: #198754;
    }

    .danger-icon {
        background: #f8d7da;
        color: #dc3545;
    }


    /* ------------------------------------------------------------
       BULK MODAL
    ------------------------------------------------------------ */

    .bulk-modal-header {
        background: linear-gradient(135deg, #0d6efd, #3d8bfd);
        color: white;
        padding: 18px 20px;
        border: none;
    }

    .bulk-selected-box {
        background: #eef5ff;
        color: #0d6efd;
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #d9e8ff;
    }

    .bulk-action {
        width: 100%;
        border: 1px solid #e5e7eb;
        background: white;
        border-radius: 12px;
        padding: 13px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: .2s ease;
    }

    .bulk-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0,0,0,.08);
    }

    .bulk-action strong {
        display: block;
        font-size: 14px;
    }

    .bulk-action small {
        display: block;
        color: #6c757d;
        margin-top: 2px;
    }

    .bulk-action-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .delete-action .bulk-action-icon {
        background: #fff0f0;
        color: #dc3545;
    }

    .deactivate-action .bulk-action-icon {
        background: #fff5df;
        color: #f59f00;
    }

    .activate-action .bulk-action-icon {
        background: #e8f8ef;
        color: #198754;
    }


    /* ------------------------------------------------------------
       LIGHTBOX
    ------------------------------------------------------------ */

    .lightbox-content {
        background: #111;
        border: none;
    }

    .lightbox-image {
        max-height: 80vh;
        object-fit: contain;
    }

    .lightbox-close {
        position: absolute;
        right: 15px;
        top: 15px;
        background-color: white;
        padding: 10px;
        z-index: 10;
    }


    /* ------------------------------------------------------------
       RESPONSIVE
    ------------------------------------------------------------ */

    @media (max-width: 768px) {

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header .d-flex {
            width: 100%;
        }

        .page-header .btn {
            flex: 1;
        }

        .summary-card {
            padding: 14px;
        }

        .product-description {
            max-width: 180px;
        }

    }

</style>

@endpush


{{-- ============================================================
    SCRIPTS
============================================================ --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Select All
    |--------------------------------------------------------------------------
    */

    const selectAll = document.getElementById('selectAll');

    const rowChecks = document.querySelectorAll('.row-check');

    const selectedCount = document.getElementById('selectedCount');


    if (selectAll) {

        selectAll.addEventListener('change', function () {

            rowChecks.forEach(function (checkbox) {

                checkbox.checked = selectAll.checked;

            });

            updateSelectedCount();

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Individual Checkbox
    |--------------------------------------------------------------------------
    */

    rowChecks.forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            updateSelectedCount();

            const checkedCount =
                document.querySelectorAll('.row-check:checked').length;

            selectAll.checked =
                checkedCount === rowChecks.length &&
                rowChecks.length > 0;

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Update Selected Count
    |--------------------------------------------------------------------------
    */

    function updateSelectedCount() {

        const count =
            document.querySelectorAll('.row-check:checked').length;

        selectedCount.textContent =
            count + (count === 1 ? ' selected' : ' selected');

    }


    /*
    |--------------------------------------------------------------------------
    | Bulk Modal
    |--------------------------------------------------------------------------
    */

    const bulkModal =
        document.getElementById('bulkActionsModal');

    if (bulkModal) {

        bulkModal.addEventListener('show.bs.modal', function () {

            const ids = getSelectedIds();

            document.getElementById('bulkSelectedText').textContent =
                ids.length +
                (ids.length === 1
                    ? ' product selected'
                    : ' products selected');

            document.getElementById('bulkProductIds').value =
                ids.join(',');

        });

    }

});


/*
|--------------------------------------------------------------------------
| Get Selected Product IDs
|--------------------------------------------------------------------------
*/

function getSelectedIds() {

    const ids = [];

    document
        .querySelectorAll('.row-check:checked')
        .forEach(function (checkbox) {

            ids.push(checkbox.value);

        });

    return ids;
}


/*
|--------------------------------------------------------------------------
| Validate Bulk Delete
|--------------------------------------------------------------------------
*/

function validateBulkDelete() {

    const ids = getSelectedIds();

    if (ids.length === 0) {

        alert('Please select at least one product.');

        return false;

    }

    document.getElementById('bulkProductIds').value =
        ids.join(',');

    return confirm(
        'Are you sure you want to delete the selected products?'
    );

}


/*
|--------------------------------------------------------------------------
| Bulk Status
|--------------------------------------------------------------------------
*/

function bulkStatus(status) {

    const ids = getSelectedIds();

    if (ids.length === 0) {

        alert('Please select at least one product.');

        return;

    }


    const statusText =
        status === 'active'
            ? 'activate'
            : 'deactivate';


    if (!confirm(
        'Are you sure you want to ' +
        statusText +
        ' the selected products?'
    )) {

        return;

    }


    fetch('{{ route('products.bulkStatus') }}', {

        method: 'POST',

        headers: {

            'Content-Type': 'application/json',

            'X-CSRF-TOKEN':
                '{{ csrf_token() }}'

        },

        body: JSON.stringify({

            product_ids: ids,

            status: status

        })

    })

    .then(function (response) {

        if (!response.ok) {

            throw new Error(
                'Something went wrong.'
            );

        }

        return response.json().catch(function () {
            return {};
        });

    })

    .then(function () {

        window.location.reload();

    })

    .catch(function (error) {

        console.error(error);

        alert(
            'Unable to update product status.'
        );

    });

}


/*
|--------------------------------------------------------------------------
| Lightbox
|--------------------------------------------------------------------------
*/

function openLightbox(src) {

    document.getElementById(
        'lightboxImage'
    ).src = src;


    const modalElement =
        document.getElementById(
            'lightboxModal'
        );


    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );


    modal.show();

}

</script>

@endpush