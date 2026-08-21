@extends('layouts.admin')

@section('content')

<div class="container-fluid py-4">

    {{-- ============================================================
        HEADER
    ============================================================ --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2">

                <div class="trash-icon">
                    <i class="fa-solid fa-trash"></i>
                </div>

                <div>

                    <h2 class="fw-bold mb-0">
                        Product Trash
                    </h2>

                    <p class="text-muted mb-0">
                        Restore or permanently delete products
                    </p>

                </div>

            </div>

        </div>


        <a
            href="{{ route('products.index') }}"
            class="btn btn-primary">
            <i class="fa-solid fa-arrow-left me-1"></i>
            Back to Products
        </a>

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
        INFO
    ============================================================ --}}
    <div class="alert alert-warning border-0 shadow-sm">

        <i class="fa-solid fa-circle-info me-2"></i>

        Products in trash are soft deleted.
        Restoring a product will bring it back to the main product list.
        Force delete permanently removes the product and its images.

    </div>


    {{-- ============================================================
        TRASH TABLE
    ============================================================ --}}
    <div class="card border-0 shadow-sm trash-card">

        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="fa-solid fa-trash-can me-2 text-danger"></i>

                        Deleted Products

                    </h5>

                    <small class="text-muted">

                        {{ $products->total() }}
                        deleted product(s)

                    </small>

                </div>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Product</th>

                        <th>Category</th>

                        <th>Price</th>

                        <th>Tags</th>

                        <th>Deleted At</th>

                        <th class="text-center">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($products as $product)

                    <tr>

                        {{-- ID --}}
                        <td>

                            <span class="fw-semibold">

                                #{{ $product->id }}

                            </span>

                        </td>


                        {{-- PRODUCT --}}
                        <td>

                            <div class="d-flex align-items-center">

                                <div class="trash-product-image me-3">

                                    @if(!empty($product->images))

                                    @php
                                    $image =
                                    $product->images[0] ?? null;
                                    @endphp

                                    @if($image)

                                    <img
                                        src="{{ asset($image) }}"
                                        alt="{{ $product->name }}">

                                    @else

                                    <i class="fa-solid fa-image"></i>

                                    @endif

                                    @elseif($product->image)

                                    <img
                                        src="{{ asset($product->image) }}"
                                        alt="{{ $product->name }}">

                                    @else

                                    <i class="fa-solid fa-image"></i>

                                    @endif

                                </div>


                                <div>

                                    <div class="fw-bold">

                                        {{ $product->name }}

                                    </div>

                                    <small class="text-muted">

                                        {{ Str::limit(
                                            $product->details,
                                            45
                                        ) }}

                                    </small>

                                </div>

                            </div>

                        </td>


                        {{-- CATEGORY --}}
                        <td>

                            <span class="category-badge">

                                {{ $product->category }}

                            </span>

                        </td>


                        {{-- PRICE --}}
                        <td>

                            <strong class="text-success">

                                ₹{{ number_format(
                                    $product->price,
                                    2
                                ) }}

                            </strong>

                        </td>


                        {{-- TAGS --}}
                        <td>

                            <div class="d-flex flex-wrap gap-1">

                                @forelse($product->tags as $tag)

                                <span class="tag-badge">

                                    {{ $tag->tag_name }}

                                </span>

                                @empty

                                <span class="text-muted small">
                                    No tags
                                </span>

                                @endforelse

                            </div>

                        </td>


                        {{-- DELETED --}}
                        <td>

                            <div class="fw-semibold">

                                {{ optional(
                                    $product->deleted_at
                                )->format(
                                    'd M Y'
                                ) }}

                            </div>

                            <small class="text-muted">

                                {{ optional(
                                    $product->deleted_at
                                )->format(
                                    'h:i A'
                                ) }}

                            </small>

                        </td>


                        {{-- ACTIONS --}}
                        <td>

                            <div class="d-flex justify-content-center gap-2">

                                {{-- RESTORE --}}
                                <form
                                    action="{{ route(
                                        'products.restore',
                                        $product->id
                                    ) }}"
                                    method="POST">

                                    @csrf
                                    @method('PUT')

                                    <button
                                        type="submit"
                                        class="btn btn-success btn-sm"
                                        onclick="return confirm(
                                            'Restore this product?'
                                        )">

                                        <i class="fa-solid fa-rotate-left me-1"></i>

                                        Restore

                                    </button>

                                </form>


                                {{-- FORCE DELETE --}}
                                <form
                                    action="{{ route(
                                        'products.forceDelete',
                                        $product->id
                                    ) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm(
                                            'This will permanently delete the product and its images. Continue?'
                                        )">

                                        <i class="fa-solid fa-trash-can me-1"></i>

                                        Delete Forever

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td
                            colspan="7"
                            class="empty-trash">

                            <div class="trash-empty-icon">

                                <i class="fa-solid fa-trash-can"></i>

                            </div>

                            <h5 class="fw-bold">
                                Trash is Empty
                            </h5>

                            <p class="text-muted">
                                There are no deleted products.
                            </p>

                            <a
                                href="{{ route('products.index') }}"
                                class="btn btn-primary">

                                <i class="fa-solid fa-box me-1"></i>

                                View Products

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

                <div class="small text-muted">

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

                </div>


                <div>

                    {{ $products->links(
                            'pagination::bootstrap-5'
                        ) }}

                </div>

            </div>

        </div>

        @endif

    </div>

</div>

@endsection


@push('styles')

<style>
    .trash-icon {

        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff0f0;
        color: #dc3545;
        font-size: 20px;

    }


    .trash-card {

        border-radius: 16px;
        overflow: hidden;

    }


    .table thead th {

        background: #f8f9fb;
        color: #495057;
        border-bottom: 1px solid #e9ecef;
        padding: 15px;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        white-space: nowrap;

    }


    .table tbody td {

        padding: 15px;
        border-color: #f0f1f3;

    }


    .trash-product-image {

        width: 55px;
        height: 55px;
        border-radius: 10px;
        background: #f1f3f5;
        color: #adb5bd;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;

    }


    .trash-product-image img {

        width: 100%;
        height: 100%;
        object-fit: cover;

    }


    .category-badge {

        background: #f4f5f7;
        color: #495057;
        border: 1px solid #e2e5e9;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;

    }


    .tag-badge {

        background: #eef6ff;
        color: #0d6efd;
        border: 1px solid #d7e9ff;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;

    }


    .empty-trash {

        text-align: center;
        padding: 80px 20px !important;

    }


    .trash-empty-icon {

        width: 75px;
        height: 75px;
        border-radius: 50%;
        background: #f1f3f5;
        color: #adb5bd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        margin: 0 auto 20px;

    }
</style>

@endpush