@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Product</h1>

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- NAME -->
        <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="name" class="form-control"
                   value="{{ $product->name }}" required>
        </div>

        <!-- DETAILS -->
        <div class="mb-3">
            <label class="form-label fw-bold">Details</label>
            <textarea name="details" class="form-control" required>{{ $product->details }}</textarea>
        </div>

        <!-- TAG SELECT -->
        @php
            $selectedTags = $product->tag_ids ?? [];
        @endphp

        <div class="mb-3">
            <label class="form-label fw-bold">Select Tags</label>
            <select name="tag_ids[]" id="tagSelect" class="form-select" multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" 
                        {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>
                        {{ $tag->tag_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- EXISTING IMAGE -->
        <div class="mb-3">
            <label class="form-label fw-bold">Existing Image</label><br>

            @if($product->image)
                <img src="{{ asset($product->image) }}" width="120" class="rounded border mb-2">
            @else
                <p class="text-muted">No Image Found</p>
            @endif
        </div>

        <!-- UPLOAD NEW IMAGE -->
        <div class="mb-3">
            <label class="form-label fw-bold">Change Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <!-- SIZE -->
        <div class="mb-3">
            <label class="form-label fw-bold">Size</label>
            <input type="text" name="size" class="form-control"
                   value="{{ $product->size }}" required>
        </div>

        <!-- COLOR -->
        <div class="mb-3">
            <label class="form-label fw-bold">Color</label>
            <input type="text" name="color" class="form-control"
                   value="{{ $product->color }}" required>
        </div>

        <!-- CATEGORY -->
        <div class="mb-3">
            <label class="form-label fw-bold">Category</label>
            <input type="text" name="category" class="form-control"
                   value="{{ $product->category }}" required>
        </div>

        <!-- PRICE -->
        <div class="mb-3">
            <label class="form-label fw-bold">Price</label>
            <input type="number" name="price" class="form-control"
                   value="{{ $product->price }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Back</a>

    </form>
</div>
@endsection


@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function(){
    $('#tagSelect').select2({
        placeholder: "Select Tags",
        allowClear: true,
        closeOnSelect: true,
        width: "100%"
    });
});
</script>
@endpush
