@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-pen me-2"></i>Edit Product</h2>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center shadow-sm mb-3">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
                @csrf @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Product Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <input type="text" name="category" class="form-control" list="categoryList" value="{{ old('category', $product->category) }}" required>
                        <datalist id="categoryList"></datalist>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Details</label>
                    <textarea name="details" class="form-control" rows="3" required>{{ old('details', $product->details) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Size</label>
                        <input type="text" name="size" class="form-control" value="{{ old('size', $product->size) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Color</label>
                        <input type="text" name="color" class="form-control" value="{{ old('color', $product->color) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Price (₹)</label>
                        <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Select Tags</label>
                        <select name="tag_ids[]" class="form-select select2-tags" multiple>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}" {{ in_array($tag->id, $product->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $tag->tag_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $product->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Existing Gallery Images</label>
                    @if(!empty($product->images))
                        <div class="row g-2 mb-2">
                            @foreach($product->images as $img)
                                <div class="col-auto">
                                    <img src="{{ asset($img) }}" width="80" class="rounded border">
                                    <button type="button" class="btn btn-outline-danger btn-sm mt-1" onclick="this.closest('.col').remove()">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <label class="form-label fw-bold mt-2">Upload New Gallery Images</label>
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    <div class="form-text">Hold Ctrl/Cmd to select multiple images. Max 2MB each.</div>
                </div>

                <div class="mb-3" id="imagePreviewContainer"></div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-check me-1"></i> Update Product</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
<style>
    #imagePreviewContainer .preview-item {
        display: inline-block;
        margin: 5px;
    }
    #imagePreviewContainer .preview-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #dee2e6;
    }
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

    loadCategories();

    $('input[name="images[]"]').on('change', function() {
        $('#imagePreviewContainer').empty();
        const files = this.files;
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreviewContainer').append(
                    '<div class="preview-item">' +
                    '<img src="' + e.target.result + '" class="rounded">' +
                    '</div>'
                );
            };
            reader.readAsDataURL(files[i]);
        }
    });
});

function loadCategories() {
    const categories = @json(\App\Models\Product::whereNotNull('category')->distinct()->pluck('category'));
    const datalist = document.getElementById('categoryList');
    categories.forEach(function(cat) {
        const option = document.createElement('option');
        option.value = cat;
        datalist.appendChild(option);
    });
}
</script>
@endpush