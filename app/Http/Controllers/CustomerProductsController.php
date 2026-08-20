<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class CustomerProductsController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('tags')
            ->where('status', 'active')

            ->when($request->filled('tag'), function ($query) use ($request) {

                $query->whereHas('tags', function ($tagQuery) use ($request) {

                    $tagQuery->where('tags.id', $request->tag);

                });

            })

            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('customer.index', compact('products'));
    }


    public function show(Product $product)
    {
        abort_if(
            $product->status !== 'active',
            404
        );

        // Load product tags
        $product->load('tags');


        /*
        |--------------------------------------------------------------------------
        | Related Products
        |--------------------------------------------------------------------------
        | Find other active products from the same category.
        |--------------------------------------------------------------------------
        */

        $relatedProducts = Product::with('tags')
            ->where('status', 'active')
            ->where('id', '!=', $product->id)
            ->where('category', $product->category)
            ->latest()
            ->take(4)
            ->get();


        return view(
            'customer.show',
            compact(
                'product',
                'relatedProducts'
            )
        );
    }
}