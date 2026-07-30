@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="fa-solid fa-boxes-stacked me-2"></i>Products
            <span class="badge bg-primary rounded-pill ms-2">{{ $products->total() }}</span>
        </h2>
        <div class="d-flex gap-2">
            <a href="javascript:void(0)" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                <i class="fa-solid fa-layer-group me-1"></i> Bulk Actions
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> Add New Product
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center shadow-sm">
            <i class="fa-solid fa-circle-check me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center shadow-sm">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width:40px;">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Product</th>
                            <th>Tags</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="form-check-input row-check">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(!empty($product->images))
                                            @php $firstImage = $product->images[0] ?? null; @endphp
                                            @if($firstImage)
                                                <img src="{{ asset($firstImage) }}" width="50" class="rounded border me-3" style="object-fit:cover;" alt="{{ $product->name }}">
                                            @else
                                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;">
                                                    <i class="fa-solid fa-image"></i>
                                                </div>
                                            @endif
                                        @elseif($product->image)
                                            <img src="{{ asset($product->image) }}" width="50" class="rounded border me-3" style="object-fit:cover;" alt="{{ $product->name }}">
                                        @else
                                            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;">
                                                <i class="fa-solid fa-image"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('products.show', $product) }}" class="text-decoration-none fw-semibold">{{ $product->name }}</a>
                                            <div class="small text-muted">{{ Str::limit($product->details, 40) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @forelse($product->tags as $tag)
                                        <span class="badge bg-info text-dark me-1">{{ $tag->tag_name }}</span>
                                    @empty
                                        <span class="text-muted">No tags</span>
                                    @endforelse
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $product->category }}</span>
                                </td>
                                <td class="fw-bold text-success">₹{{ number_format($product->price) }}</td>
                                <td>
                                    <form action="{{ route('products.toggleStatus', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @if($product->status === 'active')
                                            <span class="badge bg-success cursor-pointer" title="Click to deactivate" onclick="this.closest('form').submit();">Active</span>
                                        @else
                                            <span class="badge bg-danger cursor-pointer" title="Click to activate" onclick="this.closest('form').submit();">Inactive</span>
                                        @endif
                                    </form>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline-danger" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-box-open fa-2x mb-2 d-block"></i>
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products</span>
                <div>{{ $products->links('pagination::bootstrap-5') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fa-solid fa-layer-group me-2"></i>Bulk Actions</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('products.bulkDelete') }}" id="bulkDeleteForm">
                @csrf @method('POST')
                <div class="modal-body">
                    <input type="hidden" name="product_ids" id="bulkProductIds">
                    <p class="mb-3">Select products using the checkboxes on the left, then choose an action:</p>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete selected products?')">
                            <i class="fa-solid fa-trash me-1"></i> Delete Selected
                        </button>
                        <button type="button" class="btn btn-warning" onclick="bulkStatus('inactive')">
                            <i class="fa-solid fa-pause me-1"></i> Deactivate Selected
                        </button>
                        <button type="button" class="btn btn-success" onclick="bulkStatus('active')">
                            <i class="fa-solid fa-play me-1"></i> Activate Selected
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
});

function bulkStatus(status) {
    const ids = [];
    document.querySelectorAll('.row-check:checked').forEach(cb => ids.push(cb.value));
    if (ids.length === 0) { alert('No products selected.'); return; }
    fetch('{{ route('products.bulkStatus') }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({ product_ids: ids, status: status })
    }).then(() => window.location.reload());
}

function openLightbox(src) {
    document.getElementById('lightboxImage').src = src;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
}
</script>
@endsection

<!-- Lightbox Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark border-0">
            <div class="modal-body p-0 text-center">
                <img src="" class="img-fluid rounded" id="lightboxImage" alt="Enlarged view">
                <button type="button" class="btn btn-light btn-sm position-absolute top-0 end-0 m-2" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
<style>
    .badge.cursor-pointer { cursor: pointer; }
    .table td img { transition: transform 0.2s; }
    .table td img:hover { transform: scale(1.3); z-index: 10; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2-tags').select2({
        placeholder: 'Select product tags',
        allowClear: true,
        width: '100%'
    });
});
</script>
@endpush