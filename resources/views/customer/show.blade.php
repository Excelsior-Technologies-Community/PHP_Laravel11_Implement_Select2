@extends('layouts.customer')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fa-solid fa-box me-2"></i>{{ $product->name }}</h3>
        <span class="badge rounded-pill @if($product->status === 'active') bg-success @else bg-secondary @endif">
            {{ ucfirst($product->status) }}
        </span>
    </div>

    <div class="row">
        <div class="col-md-7 mb-4">
            @if(!empty($product->images))
                <div id="productCarousel" class="carousel slide rounded" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($product->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset($image) }}" class="d-block w-100" style="max-height:450px;object-fit:contain;background:#f0f0f0;" alt="Product Image">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            @elseif($product->image)
                <img src="{{ asset($product->image) }}" class="img-fluid rounded" style="max-height:450px;object-fit:contain;background:#f0f0f0;" alt="{{ $product->name }}">
            @else
                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height:400px;">
                    <span class="text-muted"><i class="fa-solid fa-image fa-4x"></i></span>
                </div>
            @endif
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title fw-bold">{{ $product->name }}</h4>
                    <p class="text-muted">{{ $product->details }}</p>
                    <hr>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Size</span>
                            <span>{{ $product->size }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Color</span>
                            <span>{{ $product->color }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Category</span>
                            <span class="badge bg-secondary">{{ $product->category }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Price</span>
                            <span class="fw-bold text-success fs-5">₹{{ number_format($product->price) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-semibold">Tags</span>
                            <span>
                                @forelse($product->tags as $tag)
                                    <span class="badge bg-info text-dark me-1">{{ $tag->tag_name }}</span>
                                @empty
                                    <span class="text-muted">None</span>
                                @endforelse
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if($relatedProducts->isNotEmpty())
        <div class="mt-5">
            <h5><i class="fa-solid fa-link me-2"></i>Related Products</h5>
            <div class="row g-3 mt-2">
                @foreach($relatedProducts as $related)
                    <div class="col-md-3">
                        <div class="card shadow-sm h-100 border-0">
                            <a href="{{ route('customer.products.show', $related) }}">
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
                                    <a href="{{ route('customer.products.show', $related) }}" class="text-decoration-none">{{ $related->name }}</a>
                                </h6>
                                <span class="fw-bold text-success">₹{{ number_format($related->price) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection