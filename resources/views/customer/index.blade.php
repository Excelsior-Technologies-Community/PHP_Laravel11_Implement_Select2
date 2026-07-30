@extends('layouts.customer')

@section('content')
<div class="container py-4">
    <h3 class="mb-4"><i class="fa-solid fa-store me-2"></i>Our Products</h3>

    <div class="row mb-4">
        <div class="col-md-6 mb-2">
            <div id="productSearch" style="min-width:100%;"></div>
        </div>
        <div class="col-md-6 mb-2">
            <div id="tagFilter" style="min-width:100%;"></div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center shadow-sm mb-3">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($products->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fa-solid fa-box-open fa-3x mb-3 d-block"></i>
            <h5>No products found.</h5>
        </div>
    @else
        <div class="row g-3">
            @foreach($products as $product)
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 product-card h-100">
                        <a href="{{ route('customer.products.show', $product) }}">
                            @if(!empty($product->images))
                                @php $firstImg = $product->images[0] ?? null; @endphp
                                <img src="{{ asset($firstImg ?? 'https://via.placeholder.com/300x200') }}" class="card-img-top product-img" alt="{{ $product->name }}">
                            @elseif($product->image)
                                <img src="{{ asset($product->image) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x200" class="card-img-top product-img" alt="No Image">
                            @endif
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title fw-semibold">
                                <a href="{{ route('customer.products.show', $product) }}" class="text-decoration-none">{{ $product->name }}</a>
                            </h6>
                            <p class="text-muted small flex-grow-1">{{ Str::limit($product->details, 60) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success fs-6">₹{{ number_format($product->price) }}</span>
                                <span class="badge rounded-pill @if($product->status === 'active') bg-success @else bg-secondary @endif">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </div>
                            <div class="mt-2">
                                @forelse($product->tags as $tag)
                                    <span class="badge bg-light text-dark me-1 border">{{ $tag->tag_name }}</span>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <nav class="mt-4">
            {{ $products->links('pagination::bootstrap-5') }}
        </nav>
    @endif
</div>

<!-- Lightbox Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .product-card { transition: transform 0.2s; }
    .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important; }
    .product-img { height:220px; width:100%; object-fit:cover; border-bottom:1px solid #eee; }
    #lightboxModal img { max-height:90vh; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
<script>
$(document).ready(function() {
    $('#productSearch').select2({
        placeholder: 'Search products...',
        allowClear: true,
        width: 'resolve',
        ajax: {
            url: '{{ url("/api/products/select2") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { q: params.term || '' };
            },
            processResults: function(data) {
                return { results: data };
            },
            cache: true
        },
        minimumInputLength: 1
    });

    $('#productSearch').on('select2:select', function(e) {
        window.location.href = '/customer/products/' + e.params.data.id;
    });

    $('#tagFilter').select2({
        placeholder: 'Filter by tag...',
        allowClear: true,
        width: 'resolve',
        ajax: {
            url: '{{ url("/api/tags/select2") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { q: params.term || '' };
            },
            processResults: function(data) {
                return { results: data };
            },
            cache: true
        }
    });

    $('#tagFilter').on('select2:select', function(e) {
        const tagId = e.params.data.id;
        window.location.href = '/customer/products?tag=' + tagId;
    });

    // Lightbox for clickable images
    document.querySelectorAll('[data-bs-toggle="lightbox"], .product-img, .carousel-item img').forEach(function(el) {
        el.style.cursor = 'pointer';
    });
});
</script>
@endpush