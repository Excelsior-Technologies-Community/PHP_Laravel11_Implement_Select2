<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('tags')->latest()->paginate(12);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $tags = Tag::all();
        return view('products.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'details'   => 'required|string',
            'images'    => 'nullable|array',
            'images.*'  => 'nullable|image|max:2048',
            'size'      => 'required|string',
            'color'     => 'required|string',
            'category'  => 'required|string',
            'price'     => 'required|numeric|min:0',
            'tag_ids'   => 'nullable|array',
            'status'    => 'required|in:active,inactive',
        ]);

        $product = Product::create([
            'name'      => $request->name,
            'details'   => $request->details,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'status'    => $request->status ?? 'active',
        ]);

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $images[] = 'images/' . $imageName;
            }
            $product->update(['images' => $images]);
        }

        if ($request->has('tag_ids')) {
            $product->tags()->sync($request->tag_ids);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load('tags');
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->with('tags')
            ->latest()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function edit(Product $product)
    {
        $tags = Tag::all();
        $product->load('tags');

        return view('products.edit', compact('product', 'tags'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'details'   => 'required|string',
            'images'    => 'nullable|array',
            'images.*'  => 'nullable|image|max:2048',
            'size'      => 'required|string',
            'color'     => 'required|string',
            'category'  => 'required|string',
            'price'     => 'required|numeric|min:0',
            'tag_ids'   => 'nullable|array',
            'status'    => 'required|in:active,inactive',
        ]);

        $product->update([
            'name'      => $request->name,
            'details'   => $request->details,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'status'    => $request->status,
        ]);

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $images[] = 'images/' . $imageName;
            }

            $existingImages = $product->images ?? [];
            foreach ($existingImages as $oldImage) {
                if (file_exists(public_path($oldImage))) {
                    unlink(public_path($oldImage));
                }
            }

            $product->update(['images' => $images]);
        }

        if ($request->has('tag_ids')) {
            $product->tags()->sync($request->tag_ids);
        } else {
            $product->tags()->detach();
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $images = $product->images ?? [];
        foreach ($images as $image) {
            if (file_exists(public_path($image))) {
                unlink(public_path($image));
            }
        }

        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $products = Product::whereIn('id', $request->product_ids)->get();

        foreach ($products as $product) {
            $images = $product->images ?? [];
            foreach ($images as $image) {
                if (file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $product->delete();
        }

        return redirect()->route('products.index')
            ->with('success', 'Selected products deleted successfully.');
    }

    public function bulkStatus(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'status' => 'required|in:active,inactive',
        ]);

        Product::whereIn('id', $request->product_ids)->update(['status' => $request->status]);

        return redirect()->route('products.index')
            ->with('success', 'Selected products status updated.');
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.index')
            ->with('success', 'Product restored successfully.');
    }

    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        $images = $product->images ?? [];
        foreach ($images as $image) {
            if (file_exists(public_path($image))) {
                unlink(public_path($image));
            }
        }
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->forceDelete();

        return redirect()->route('products.index')
            ->with('success', 'Product permanently deleted.');
    }

    public function trashed()
    {
        $products = Product::onlyTrashed()->with('tags')->paginate(12);

        return view('products.trashed', compact('products'));
    }

    public function toggleStatus(Product $product)
    {
        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Product status changed to ' . $newStatus);
    }

    public function select2Search(Request $request)
    {
        $term = $request->get('q', '');

        $query = Product::with('tags');

        if ($term) {
            $query->where('name', 'like', '%' . $term . '%')
                ->orWhere('category', 'like', '%' . $term . '%');
        }

        $products = $query->latest()->take(20)->get();

        return response()->json($products->map(function ($product) {
            return [
                'id' => $product->id,
                'text' => $product->name . ' - ' . $product->category,
            ];
        }));
    }
}