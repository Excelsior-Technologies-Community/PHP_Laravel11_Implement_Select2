@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="fa-solid fa-box me-2"></i>{{ $product->name }}
            <span class="badge rounded-pill ms-2 @if($product->status === 'active') bg-success @else bg-danger @endif">
                {{ ucfirst($product->status) }}
            </span>
        </h2>
        <div class="btn-group">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pen me-1"></i> Edit
            </a>
            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product permanently?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash me-1"></i> Delete</button>
            </form>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center shadow-sm mb-4">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="fa-solid fa-images me-2"></i>Product Gallery
                </div>
                <div class="card-body">
                    @if(!empty($product->images))
                        <div class="row g-2">
                            @foreach($product->images as $image)
                                <div class="col-4">
                                    <img src="{{ asset($image) }}" class="img-thumbnail rounded w-100 cursor-pointer" style="height:120px;object-fit:cover;" alt="Product Image" onclick="openLightbox('{{ asset($image) }}')">
                                </div>
                            @endforeach
                        </div>
                    @elseif($product->image)
                        <img src="{{ asset($product->image) }}" class="img-thumbnail rounded w-100 cursor-pointer" style="max-height:300px;object-fit:cover;" alt="{{ $product->name }}" onclick="openLightbox('{{ asset($product->image) }}')">
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fa-solid fa-image fa-3x mb-2 d-block"></i>
                            No images uploaded
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="fa-solid fa-circle-info me-2"></i>Product Details
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="fw-bold" style="width:130px;">Name</td>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Details</td>
                            <td>{{ $product->details }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Size</td>
                            <td>{{ $product->size }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Color</td>
                            <td>{{ $product->color }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Category</td>
                            <td><span class="badge bg-secondary">{{ $product->category }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Price</td>
                            <td class="fw-bold text-success fs-5">₹{{ number_format($product->price) }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Tags</td>
                            <td>
                                @forelse($product->tags as $tag)
                                    <span class="badge bg-info text-dark me-1">{{ $tag->tag_name }}</span>
                                @empty
                                    <span class="text-muted">No tags assigned</span>
                                @endforelse
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Created</td>
                            <td>{{ $product->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($relatedProducts->isNotEmpty())
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold">
                <i class="fa-solid fa-link me-2"></i>Related Products (Same Category)
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($relatedProducts as $related)
                        <div class="col-md-3">
                            <div class="card shadow-sm h-100 border-0">
                                <a href="{{ route('products.show', $related) }}">
                                    @if(!empty($related->images))
                                        @php $relImg = $related->images[0] ?? null; @endphp
                                        <img src="{{ asset($relImg ?? 'https://via.placeholder.com/300x200') }}" class="card-img-top" style="height:140px;object-fit:cover;" alt="{{ $related->name }}">
                                    @elseif($related->image)
                                        <img src="{{ asset($related->image) }}" class="card-img-top" style="height:140px;object-fit:cover;" alt="{{ $related->name }}">
                                    @else
                                        <img src="https://via.placeholder.com/300x200" class="card-img-top" style="height:140px;object-fit:cover;" alt="No Image">
                                    @endif
                                </a>
                                <div class="card-body p-3">
                                    <h6 class="card-title fw-semibold mb-1">
                                        <a href="{{ route('products.show', $related) }}" class="text-decoration-none">{{ $related->name }}</a>
                                    </h6>
                                    <p class="text-muted small mb-1">{{ Str::limit($related->details, 40) }}</p>
                                    <span class="fw-bold text-success">₹{{ number_format($related->price) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

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

@endsection

@push('styles')
<style>
    .cursor-pointer { cursor: pointer; transition: transform 0.2s; }
    .cursor-pointer:hover { transform: scale(1.05); }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
<script>
function openLightbox(src) {
    document.getElementById('lightboxImage').src = src;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
}
</script>
@endpush