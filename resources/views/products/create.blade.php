@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Create Product</h1>

    <!-- Form to create a new product, posts data to 'products.store' route with multipart form data for image upload -->
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Product Name input -->
        <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <!-- Product Details textarea -->
        <div class="mb-3">
            <label class="form-label fw-bold">Details</label>
            <textarea name="details" class="form-control" required></textarea>
        </div>

        <!-- Multiple select dropdown for Tags -->
        <div class="mb-3">
            <label class="form-label fw-bold">Select Tags</label>
            <select name="tag_ids[]" class="form-control select2-tags" multiple>
                <!-- Dynamically fill options from $tags passed from controller -->
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->tag_name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Single image file input for product image -->
        <div class="mb-3">
            <label class="form-label fw-bold">Product Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <!-- Product size text input -->
        <div class="mb-3">
            <label class="form-label fw-bold">Size</label>
            <input type="text" name="size" class="form-control" required>
        </div>

        <!-- Product color text input -->
        <div class="mb-3">
            <label class="form-label fw-bold">Color</label>
            <input type="text" name="color" class="form-control" required>
        </div>

        <!-- Product category text input -->
        <div class="mb-3">
            <label class="form-label fw-bold">Category</label>
            <input type="text" name="category" class="form-control" required>
        </div>

        <!-- Product price number input -->
        <div class="mb-3">
            <label class="form-label fw-bold">Price</label>
            <input type="number" name="price" class="form-control" required>
        </div>

        <!-- Submit button to create product -->
        <button type="submit" class="btn btn-primary">Create Product</button>
        <!-- Back button to return to product list -->
        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-2">Back</a>
    </form>
</div>
@endsection


@push('scripts')
<!-- Include Select2 CSS and JS for enhanced multi-select dropdown UI -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// Initialize Select2 plugin on the tags multi-select
$(document).ready(function() {
    $('.select2-tags').select2({
        placeholder: "Select product tags",
        allowClear: true,
        closeOnSelect: true,
        width: "100%"
    });
});
</script>
@endpush
