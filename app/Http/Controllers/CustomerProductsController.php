<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class CustomerProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('tags')
            ->where('status', 'active');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tag')) {
            $tagId = $request->tag;
            $query->whereHas('tags', function ($q) use ($tagId) {
                $q->where('tags.id', $tagId);
            });
        }

        $products = $query->latest()->paginate(12);

        $tags = Tag::latest()->get();

        return view('customer.index', compact('products', 'tags'));
    }

    public function show(Product $product)
    {
        $product->load('tags');
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->with('tags')
            ->latest()
            ->take(4)
            ->get();

        return view('customer.show', compact('product', 'relatedProducts'));
    }
}