<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Show all products
    // Fetches all products from the database and returns the view 'products.index' passing products data
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // Show create form
    // Fetches all tags and returns the view 'products.create' so user can add a new product with available tags
    public function create()
    {
        $tags = Tag::all();
        return view('products.create', compact('tags'));
    }

    // Store product (SINGLE IMAGE)
    // Validates user input, handles single image upload if provided, stores the product data including image path and tags
    public function store(Request $request)
    {
        // Validation rules to ensure required fields are correctly filled
        $request->validate([
            'name'      => 'required',
            'details'   => 'required',
            'size'      => 'required',
            'color'     => 'required',
            'category'  => 'required',
            'price'     => 'required|numeric',
            'image'     => 'nullable|image|max:2048',
            'tag_ids'   => 'nullable|array',
        ]);

        $imagePath = null;

        // Check if an image file is uploaded
        if ($request->hasFile('image')) {
            // Generate unique filename and move image to 'public/images' directory
            $imageName = time() . '_' . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);

            // Set relative path to store in database
            $imagePath = 'images/' . $imageName;
        }

        // Create product record in database including image path and associated tag IDs
        Product::create([
            'name'      => $request->name,
            'details'   => $request->details,
            'image'     => $imagePath,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'tag_ids'   => $request->tag_ids,
        ]);

        // Redirect to product listing with success message
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    // Edit form
    // Fetches product to edit and all tags, returns 'products.edit' view with current product and tags for modification
    public function edit(Product $product)
    {
        $tags = Tag::all();
        return view('products.edit', compact('product', 'tags'));
    }

    // Update product
    // Validates input, processes new image upload (deletes old image if applicable), updates product data in database
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'      => 'required',
            'details'   => 'required',
            'size'      => 'required',
            'color'     => 'required',
            'category'  => 'required',
            'price'     => 'required|numeric',
            'image'     => 'nullable|image|max:2048',
            'tag_ids'   => 'nullable|array',
        ]);

        $imagePath = $product->image;

        // If a new image file is uploaded, process it
        if ($request->hasFile('image')) {
            // Delete old image from server if it exists
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            // Store new image and update path
            $imageName = time() . '_' . uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);

            $imagePath = 'images/' . $imageName;
        }

        // Update product record with new data including possibly new image path
        $product->update([
            'name'      => $request->name,
            'details'   => $request->details,
            'image'     => $imagePath,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'tag_ids'   => $request->tag_ids,
        ]);

        // Redirect to product listing with success message
        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    // Delete product permanently
    // Deletes product image file from server if exists, removes product record from database
    public function destroy(Product $product)
    {
        // Remove product image file from storage if present
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        // Delete product row from database
        $product->delete();

        // Redirect to product listing with success message
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
