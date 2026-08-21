@extends('layouts.admin')

@section('content')

<div class="container-fluid py-4">

    {{-- ============================================================
        PAGE HEADER
    ============================================================ --}}
    <div class="page-header mb-4">

        <div class="d-flex align-items-center gap-2">

            <div class="page-icon">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>

            <div>
                <h2 class="fw-bold mb-0">Products</h2>

                <p class="text-muted mb-0">
                    Advanced product management
                </p>
            </div>

        </div>

        <div class="d-flex gap-2 flex-wrap">

            {{-- EXPORT --}}
            <a
                href="{{ route('products.export', request()->query()) }}"
                class="btn btn-outline-success">
                <i class="fa-solid fa-file-csv me-1"></i>
                Export CSV
            </a>

            {{-- BULK ACTIONS --}}
            <button
                type="button"
                class="btn btn-outline-primary"
                data-bs-toggle="modal"
                data-bs-target="#bulkActionsModal">
                <i class="fa-solid fa-layer-group me-1"></i>
                Bulk Actions
            </button>

            {{-- ADD PRODUCT --}}
            <a
                href="{{ route('products.create') }}"
                class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i>
                Add Product
            </a>

        </div>

    </div>


    {{-- ============================================================
        SUCCESS
    ============================================================ --}}
    @if(session('success'))

    <div class="alert alert-success alert-dismissible fade show shadow-sm">

        <i class="fa-solid fa-circle-check me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>

    </div>

    @endif


    {{-- ============================================================
        ERROR
    ============================================================ --}}
    @if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show shadow-sm">

        <i class="fa-solid fa-circle-exclamation me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"></button>

    </div>

    @endif


    {{-- ============================================================
        SUMMARY CARDS
    ============================================================ --}}
    <div class="row g-3 mb-4">

        {{-- TOTAL --}}
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


        {{-- ACTIVE --}}
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


        {{-- TAGS --}}
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
        ADVANCED SEARCH
    ============================================================ --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex align-items-center gap-2">

                <div class="filter-icon">
                    <i class="fa-solid fa-filter"></i>
                </div>

                <div>

                    <h5 class="fw-bold mb-1">
                        Advanced Product Search
                    </h5>

                    <small class="text-muted">
                        Search and filter products by multiple conditions
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-4">

            <form
                method="GET"
                action="{{ route('products.index') }}">

                <div class="row g-3">

                    {{-- SEARCH --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Search product name, details, category...">

                        </div>

                    </div>


                    {{-- CATEGORY --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Category
                        </label>

                        <select
                            name="category"
                            class="form-select">

                            <option value="">
                                All Categories
                            </option>

                            @foreach($categories as $category)

                            <option
                                value="{{ $category }}"
                                @selected(request('category')==$category)>
                                {{ $category }}
                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- STATUS --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="active"
                                @selected(request('status')==='active' )>
                                Active
                            </option>

                            <option
                                value="inactive"
                                @selected(request('status')==='inactive' )>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- TAG --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Tag
                        </label>

                        <select
                            name="tag"
                            class="form-select">

                            <option value="">
                                All Tags
                            </option>

                            @foreach($tags as $tag)

                            <option
                                value="{{ $tag->id }}"
                                @selected(request('tag')==$tag->id)
                                >
                                {{ $tag->tag_name }}
                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- SORT --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Sort By
                        </label>

                        <select
                            name="sort"
                            class="form-select">

                            <option
                                value="newest"
                                @selected(request('sort', 'newest' )==='newest' )>
                                Newest First
                            </option>

                            <option
                                value="oldest"
                                @selected(request('sort')==='oldest' )>
                                Oldest First
                            </option>

                            <option
                                value="name_asc"
                                @selected(request('sort')==='name_asc' )>
                                Name A-Z
                            </option>

                            <option
                                value="name_desc"
                                @selected(request('sort')==='name_desc' )>
                                Name Z-A
                            </option>

                            <option
                                value="price_asc"
                                @selected(request('sort')==='price_asc' )>
                                Price Low-High
                            </option>

                            <option
                                value="price_desc"
                                @selected(request('sort')==='price_desc' )>
                                Price High-Low
                            </option>

                            <option
                                value="category_asc"
                                @selected(request('sort')==='category_asc' )>
                                Category A-Z
                            </option>

                            <option
                                value="category_desc"
                                @selected(request('sort')==='category_desc' )>
                                Category Z-A
                            </option>

                        </select>

                    </div>


                    {{-- MIN PRICE --}}
                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Min Price
                        </label>

                        <input
                            type="number"
                            name="min_price"
                            class="form-control"
                            value="{{ request('min_price') }}"
                            placeholder="₹ Min"
                            min="0">

                    </div>


                    {{-- MAX PRICE --}}
                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Max Price
                        </label>

                        <input
                            type="number"
                            name="max_price"
                            class="form-control"
                            value="{{ request('max_price') }}"
                            placeholder="₹ Max"
                            min="0">

                    </div>


                    {{-- BUTTONS --}}
                    <div class="col-12">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary">
                                <i class="fa-solid fa-filter me-1"></i>
                                Apply Filters
                            </button>

                            <a
                                href="{{ route('products.index') }}"
                                class="btn btn-outline-secondary">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================
        PRODUCT TABLE
    ============================================================ --}}
    <div class="card product-table-card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="fa-solid fa-list me-2 text-primary"></i>

                        Product List

                    </h5>

                    <small class="text-muted">

                        Showing
                        {{ $products->firstItem() ?? 0 }}
                        -
                        {{ $products->lastItem() ?? 0 }}
                        of
                        {{ $products->total() }}
                        products

                    </small>

                </div>


                <div
                    class="selected-count"
                    id="selectedCount">
                    0 selected
                </div>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th width="45">
                            <input
                                type="checkbox"
                                id="selectAll"
                                class="form-check-input">
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
                                value="{{ $product->id }}"
                                class="form-check-input row-check">

                        </td>


                        {{-- PRODUCT --}}
                        <td>

                            <div class="d-flex align-items-center">

                                {{-- IMAGE --}}
                                <div class="product-image-wrapper me-3">

                                    @php

                                    $firstImage = null;

                                    if (is_array($product->images)) {
                                    $firstImage = $product->images[0] ?? null;
                                    }

                                    @endphp


                                    @if($firstImage)

                                    <img
                                        src="{{ asset($firstImage) }}"
                                        class="product-image"
                                        alt="{{ $product->name }}"
                                        onclick="openLightbox('{{ asset($firstImage) }}')">

                                    @elseif(!empty($product->image))

                                    <img
                                        src="{{ asset($product->image) }}"
                                        class="product-image"
                                        alt="{{ $product->name }}"
                                        onclick="openLightbox('{{ asset($product->image) }}')">

                                    @else

                                    <div class="product-placeholder">

                                        <i class="fa-solid fa-image"></i>

                                    </div>

                                    @endif

                                </div>


                                {{-- INFO --}}
                                <div>

                                    <a
                                        href="{{ route('products.show', $product) }}"
                                        class="product-name">
                                        {{ $product->name }}
                                    </a>

                                    <div class="product-description">

                                        {{ Str::limit($product->details, 60) }}

                                    </div>

                                    <div class="product-meta">

                                        <i class="fa-solid fa-hashtag"></i>

                                        {{ $product->id }}

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
                                method="POST">

                                @csrf

                                @if($product->status === 'active')

                                <button
                                    type="submit"
                                    class="status-badge status-active">

                                    <span class="status-dot"></span>

                                    Active

                                </button>

                                @else

                                <button
                                    type="submit"
                                    class="status-badge status-inactive">

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
                                    title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>


                                {{-- EDIT --}}
                                <a
                                    href="{{ route('products.edit', $product) }}"
                                    class="action-btn edit-btn"
                                    title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>


                                {{-- DELETE --}}
                                <form
                                    action="{{ route('products.destroy', $product) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Move this product to trash?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="action-btn delete-btn"
                                        title="Delete">

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
                            class="empty-state">

                            <div class="empty-icon">

                                <i class="fa-solid fa-box-open"></i>

                            </div>

                            <h5>
                                No Products Found
                            </h5>

                            <p class="text-muted">
                                Try changing your search or filters.
                            </p>

                            <a
                                href="{{ route('products.create') }}"
                                class="btn btn-primary">

                                <i class="fa-solid fa-plus me-1"></i>

                                Add Product

                            </a>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ========================================================
            PAGINATION
        ========================================================= --}}
        @if($products->hasPages())

        <div class="card-footer bg-white border-0 p-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="text-muted small">

                    Showing

                    <strong>
                        {{ $products->firstItem() ?? 0 }}
                    </strong>

                    -

                    <strong>
                        {{ $products->lastItem() ?? 0 }}
                    </strong>

                    of

                    <strong>
                        {{ $products->total() }}
                    </strong>

                    products

                </div>


                <div>

                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}

                </div>

            </div>

        </div>

        @endif

    </div>

</div>



{{-- ================================================================
    BULK ACTIONS MODAL
================================================================ --}}
<div
    class="modal fade"
    id="bulkActionsModal"
    tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bulk-modal-header">

                <div>

                    <h5 class="modal-title fw-bold">

                        <i class="fa-solid fa-layer-group me-2"></i>

                        Bulk Actions

                    </h5>

                    <small>
                        Manage multiple products at once
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"></button>

            </div>


            <form
                method="POST"
                action="{{ route('products.bulkDelete') }}"
                id="bulkDeleteForm">

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
                        id="bulkProductIds">


                    {{-- DELETE --}}
                    <button
                        type="submit"
                        class="bulk-action delete-action mb-3"
                        onclick="return validateBulkDelete()">

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
                        class="bulk-action deactivate-action mb-3"
                        onclick="bulkStatus('inactive')">

                        <span class="bulk-action-icon">

                            <i class="fa-solid fa-pause"></i>

                        </span>

                        <span class="text-start">

                            <strong>
                                Deactivate Selected
                            </strong>

                            <small>
                                Set selected products inactive
                            </small>

                        </span>

                    </button>


                    {{-- ACTIVATE --}}
                    <button
                        type="button"
                        class="bulk-action activate-action"
                        onclick="bulkStatus('active')">

                        <span class="bulk-action-icon">

                            <i class="fa-solid fa-play"></i>

                        </span>

                        <span class="text-start">

                            <strong>
                                Activate Selected
                            </strong>

                            <small>
                                Set selected products active
                            </small>

                        </span>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- ================================================================
    LIGHTBOX
================================================================ --}}
<div
    class="modal fade"
    id="lightboxModal"
    tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content lightbox-content">

            <div class="modal-body p-0 position-relative text-center">

                <img
                    src=""
                    id="lightboxImage"
                    class="img-fluid lightbox-image"
                    alt="Product image">

                <button
                    type="button"
                    class="btn-close lightbox-close"
                    data-bs-dismiss="modal"></button>

            </div>

        </div>

    </div>

</div>

@endsection



{{-- ================================================================
    STYLES
================================================================ --}}
@push('styles')

<link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    rel="stylesheet">

<style>
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

    .summary-card {
        background: #fff;
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, .05);
        border: 1px solid #edf0f4;
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
    }

    .summary-value {
        font-size: 22px;
        font-weight: 700;
    }

    .filter-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #eef4ff;
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-table-card {
        border-radius: 16px;
        overflow: hidden;
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
        transition: .2s;
    }

    .product-image:hover {
        transform: scale(1.08);
        box-shadow: 0 5px 15px rgba(0, 0, 0, .15);
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

    .product-name {
        color: #212529;
        font-weight: 700;
        text-decoration: none;
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

    .price {
        color: #198754;
        font-weight: 700;
        white-space: nowrap;
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

    .selected-count {
        background: #f1f5ff;
        color: #0d6efd;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
    }

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

    .bulk-action strong {
        display: block;
    }

    .bulk-action small {
        display: block;
        color: #6c757d;
    }

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
        background: white;
        padding: 10px;
        z-index: 10;
    }

    @media(max-width:768px) {

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

    }
</style>

@endpush



{{-- ================================================================
    SCRIPTS
================================================================ --}}
@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const selectAll =
            document.getElementById('selectAll');

        const rowChecks =
            document.querySelectorAll('.row-check');

        const selectedCount =
            document.getElementById('selectedCount');


        /*
        |--------------------------------------------------------------------------
        | SELECT ALL
        |--------------------------------------------------------------------------
        */

        if (selectAll) {

            selectAll.addEventListener('change', function() {

                rowChecks.forEach(function(checkbox) {

                    checkbox.checked =
                        selectAll.checked;

                });

                updateSelectedCount();

            });

        }


        /*
        |--------------------------------------------------------------------------
        | INDIVIDUAL CHECKBOX
        |--------------------------------------------------------------------------
        */

        rowChecks.forEach(function(checkbox) {

            checkbox.addEventListener('change', function() {

                updateSelectedCount();

                const checkedCount =
                    document.querySelectorAll(
                        '.row-check:checked'
                    ).length;

                selectAll.checked =
                    checkedCount === rowChecks.length &&
                    rowChecks.length > 0;

            });

        });


        /*
        |--------------------------------------------------------------------------
        | UPDATE COUNT
        |--------------------------------------------------------------------------
        */

        function updateSelectedCount() {

            const count =
                document.querySelectorAll(
                    '.row-check:checked'
                ).length;

            selectedCount.textContent =
                count + ' selected';

        }


        /*
        |--------------------------------------------------------------------------
        | BULK MODAL
        |--------------------------------------------------------------------------
        */

        const bulkModal =
            document.getElementById(
                'bulkActionsModal'
            );

        if (bulkModal) {

            bulkModal.addEventListener(
                'show.bs.modal',
                function() {

                    const ids =
                        getSelectedIds();

                    document.getElementById(
                            'bulkSelectedText'
                        ).textContent =
                        ids.length +
                        (ids.length === 1 ?
                            ' product selected' :
                            ' products selected');

                    document.getElementById(
                            'bulkProductIds'
                        ).value =
                        ids.join(',');

                }
            );

        }

    });


    /*
    |--------------------------------------------------------------------------
    | GET SELECTED IDS
    |--------------------------------------------------------------------------
    */

    function getSelectedIds() {

        const ids = [];

        document
            .querySelectorAll('.row-check:checked')
            .forEach(function(checkbox) {

                ids.push(checkbox.value);

            });

        return ids;

    }


    /*
    |--------------------------------------------------------------------------
    | BULK DELETE
    |--------------------------------------------------------------------------
    */

    function validateBulkDelete() {

        const ids =
            getSelectedIds();

        if (ids.length === 0) {

            alert(
                'Please select at least one product.'
            );

            return false;

        }

        document.getElementById(
                'bulkProductIds'
            ).value =
            ids.join(',');

        return confirm(
            'Are you sure you want to delete the selected products?'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BULK STATUS
    |--------------------------------------------------------------------------
    */

    function bulkStatus(status) {

        const ids =
            getSelectedIds();

        if (ids.length === 0) {

            alert(
                'Please select at least one product.'
            );

            return;

        }

        const text =
            status === 'active' ?
            'activate' :
            'deactivate';

        if (!confirm(
                'Are you sure you want to ' +
                text +
                ' the selected products?'
            )) {

            return;

        }


        fetch(
                '{{ route("products.bulkStatus") }}', {
                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN': '{{ csrf_token() }}'

                    },

                    body: JSON.stringify({

                        product_ids: ids,

                        status: status

                    })

                }
            )
            .then(function(response) {

                if (!response.ok) {

                    throw new Error(
                        'Something went wrong.'
                    );

                }

                return response.json();

            })
            .then(function() {

                window.location.reload();

            })
            .catch(function(error) {

                console.error(error);

                alert(
                    'Unable to update product status.'
                );

            });

    }


    /*
    |--------------------------------------------------------------------------
    | LIGHTBOX
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