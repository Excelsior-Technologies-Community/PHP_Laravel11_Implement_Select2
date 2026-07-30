@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="fa-solid fa-trash-can me-2"></i>Trashed Products
            <span class="badge bg-danger rounded-pill ms-2">{{ $products->total() }}</span>
        </h2>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Deleted On</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(!empty($product->images))
                                            @php $trImg = $product->images[0] ?? null; @endphp
                                            @if($trImg)
                                                <img src="{{ asset($trImg) }}" width="40" class="rounded border me-3" style="object-fit:cover;">
                                            @endif
                                        @elseif($product->image)
                                            <img src="{{ asset($product->image) }}" width="40" class="rounded border me-3" style="object-fit:cover;">
                                        @endif
                                        <div>
                                            <span class="fw-semibold">{{ $product->name }}</span>
                                            <div class="small text-muted">{{ Str::limit($product->details, 30) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->category }}</td>
                                <td>{{ $product->deleted_at->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('products.restore', $product) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <button class="btn btn-outline-success btn-sm me-1" title="Restore">
                                            <i class="fa-solid fa-rotate-left"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('products.forceDelete', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently delete this product?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" title="Permanent Delete">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No trashed products.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection