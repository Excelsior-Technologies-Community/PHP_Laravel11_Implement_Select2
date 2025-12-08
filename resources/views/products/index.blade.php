@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <!-- Header section with title and Add New Product button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📦 Products List</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">➕ Add New Product</a>
    </div>

    <!-- Display success message from session flash data (set by controller) -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <!-- Main card container for products table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <!-- Responsive table showing all products -->
                <table class="table table-hover mb-0 align-middle">
                    <!-- Dark header row with column titles -->
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th width="20%">Details</th>
                            <th>Image</th>
                            <th>Tags</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Category</th>
                            <th>Price (₹)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Loop through products collection from controller -->
                        @forelse($products as $product)
                            <!-- Convert stored tag_ids (JSON/array) to actual tag names from database -->
                            @php
                                // Convert tag_ids to array safely (handles both JSON string and array storage)
                                $tagIds = is_array($product->tag_ids) ? $product->tag_ids : json_decode($product->tag_ids, true);
                                // Fetch tag names for this product's tag IDs
                                $tags = \App\Models\Tag::whereIn('id', $tagIds ?? [])->pluck('tag_name');
                            @endphp

                            <!-- Product row data -->
                            <tr>
                                <!-- Product name (bold) -->
                                <td class="fw-semibold">{{ $product->name }}</td>

                                <!-- Truncated details (max 60 chars) -->
                                <td>{{ Str::limit($product->details, 60) }}</td>

                                <!-- SINGLE IMAGE DISPLAY -->
                                <td>
                                    @if($product->image)
                                        <!-- Show product image if exists (60px width, styled) -->
                                        <img src="{{ asset($product->image) }}" width="60" 
                                             class="rounded border shadow-sm">
                                    @else
                                        <!-- Placeholder text if no image -->
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>

                                <!-- TAGS DISPLAY as badges -->
                                <td>
                                    @forelse($tags as $tag)
                                        <!-- Show each tag as styled badge -->
                                        <span class="badge bg-info text-dark me-1">{{ $tag }}</span>
                                    @empty
                                        <!-- Show if no tags assigned -->
                                        <span class="text-muted">No Tags</span>
                                    @endforelse
                                </td>

                                <!-- Simple text fields -->
                                <td>{{ $product->size }}</td>
                                <td>{{ $product->color }}</td>
                                <td>{{ $product->category }}</td>

                                <!-- Formatted price with Indian Rupee symbol -->
                                <td class="fw-bold text-success">₹{{ number_format($product->price) }}</td>

                                <!-- Action buttons column (centered) -->
                                <td class="text-center">
                                    <!-- Edit button linking to edit form -->
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="btn btn-warning btn-sm me-1">✏ Edit</a>

                                    <!-- Delete form (hidden, uses JavaScript confirm) -->
                                    <form action="{{ route('products.destroy', $product) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <!-- Delete button with confirmation dialog -->
                                        <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this product?')">
                                            🗑 Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <!-- Empty state when no products exist -->
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
