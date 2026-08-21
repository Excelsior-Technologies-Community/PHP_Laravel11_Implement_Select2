<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    /**
     * ============================================================
     * PRODUCT INDEX
     * Advanced Search + Filters + Sorting + Pagination
     * ============================================================
     */
    public function index(Request $request)
    {
        $query = Product::with('tags');

        $this->applyFilters($query, $request);

        $products = $query
            ->paginate(5)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $categories = Product::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $tags = Tag::orderBy('tag_name')->get();

        return view('products.index', compact(
            'products',
            'categories',
            'tags'
        ));
    }


    /**
     * ============================================================
     * CREATE
     * ============================================================
     */
    public function create()
    {
        $tags = Tag::orderBy('tag_name')->get();

        return view('products.create', compact('tags'));
    }


    /**
     * ============================================================
     * STORE
     * ============================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'details'   => 'required|string',
            'images'    => 'nullable|array',
            'images.*'  => 'nullable|image|max:2048',
            'size'      => 'required|string',
            'color'     => 'required|string',
            'category'  => 'required|string|max:255',
            'price'     => 'required|numeric|min:0',
            'tag_ids'   => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
            'status'    => 'required|in:active,inactive',
        ]);

        $product = Product::create([
            'name'      => $request->name,
            'details'   => $request->details,
            'size'      => $request->size,
            'color'     => $request->color,
            'category'  => $request->category,
            'price'     => $request->price,
            'status'    => $request->status,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Images
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('images')) {

            $images = [];

            foreach ($request->file('images') as $image) {

                $imageName =
                    time() . '_' .
                    uniqid() . '.' .
                    $image->getClientOriginalExtension();

                $image->move(
                    public_path('images'),
                    $imageName
                );

                $images[] = 'images/' . $imageName;
            }

            $product->update([
                'images' => $images
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Sync Tags
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tag_ids')) {
            $product->tags()->sync($request->tag_ids);
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }


    /**
     * ============================================================
     * SHOW
     * ============================================================
     */
    public function show(Product $product)
    {
        $product->load('tags');

        $relatedProducts = Product::where('status', 'active')
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->with('tags')
            ->latest()
            ->take(4)
            ->get();

        return view(
            'products.show',
            compact(
                'product',
                'relatedProducts'
            )
        );
    }


    /**
     * ============================================================
     * EDIT
     * ============================================================
     */
    public function edit(Product $product)
    {
        $tags = Tag::orderBy('tag_name')->get();

        $product->load('tags');

        return view(
            'products.edit',
            compact(
                'product',
                'tags'
            )
        );
    }


    /**
     * ============================================================
     * UPDATE
     * ============================================================
     */
    public function update(
        Request $request,
        Product $product
    ) {
        $request->validate([
            'name'      => 'required|string|max:255',
            'details'   => 'required|string',
            'images'    => 'nullable|array',
            'images.*'  => 'nullable|image|max:2048',
            'size'      => 'required|string',
            'color'     => 'required|string',
            'category'  => 'required|string|max:255',
            'price'     => 'required|numeric|min:0',
            'tag_ids'   => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
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

        /*
        |--------------------------------------------------------------------------
        | Replace Images
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('images')) {

            $newImages = [];

            foreach ($request->file('images') as $image) {

                $imageName =
                    time() . '_' .
                    uniqid() . '.' .
                    $image->getClientOriginalExtension();

                $image->move(
                    public_path('images'),
                    $imageName
                );

                $newImages[] = 'images/' . $imageName;
            }

            /*
            |--------------------------------------------------------------------------
            | Delete old image files
            |--------------------------------------------------------------------------
            */

            $existingImages = $product->images ?? [];

            foreach ($existingImages as $oldImage) {

                if (
                    $oldImage &&
                    file_exists(public_path($oldImage))
                ) {
                    unlink(public_path($oldImage));
                }
            }

            if (
                $product->image &&
                file_exists(public_path($product->image))
            ) {
                unlink(public_path($product->image));
            }

            $product->update([
                'images' => $newImages,
                'image'  => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Tags
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tag_ids')) {

            $product->tags()->sync(
                $request->tag_ids
            );
        } else {

            $product->tags()->detach();
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }


    /**
     * ============================================================
     * SOFT DELETE
     *
     * IMPORTANT:
     * Do NOT delete image files here.
     * Otherwise Restore will bring back a product without images.
     * ============================================================
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Product moved to trash successfully.'
            );
    }


    /**
     * ============================================================
     * BULK SOFT DELETE
     * ============================================================
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'product_ids'   => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $products = Product::whereIn(
            'id',
            $request->product_ids
        )->get();

        foreach ($products as $product) {

            /*
             * Do NOT delete image files.
             * Product is only soft deleted.
             */

            $product->delete();
        }

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Selected products moved to trash successfully.'
            );
    }


    /**
     * ============================================================
     * BULK STATUS
     * ============================================================
     */
    public function bulkStatus(Request $request)
    {
        $request->validate([
            'product_ids'   => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'status'        => 'required|in:active,inactive',
        ]);

        Product::whereIn(
            'id',
            $request->product_ids
        )->update([
            'status' => $request->status
        ]);

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                'Selected products status updated successfully.'
            );
    }


    /**
     * ============================================================
     * RESTORE
     * ============================================================
     */
    public function restore($id)
    {
        $product = Product::onlyTrashed()
            ->findOrFail($id);

        $product->restore();

        return redirect()
            ->route('products.trashed')
            ->with(
                'success',
                'Product restored successfully.'
            );
    }


    /**
     * ============================================================
     * FORCE DELETE
     * ============================================================
     */
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Delete Gallery Images
        |--------------------------------------------------------------------------
        */

        $images = $product->images ?? [];

        foreach ($images as $image) {

            if (
                $image &&
                file_exists(public_path($image))
            ) {
                unlink(public_path($image));
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Old Single Image
        |--------------------------------------------------------------------------
        */

        if (
            $product->image &&
            file_exists(public_path($product->image))
        ) {
            unlink(public_path($product->image));
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Database Record Permanently
        |--------------------------------------------------------------------------
        */

        $product->forceDelete();

        return redirect()
            ->route('products.trashed')
            ->with(
                'success',
                'Product permanently deleted.'
            );
    }


    /**
     * ============================================================
     * TRASH
     * ============================================================
     */
    public function trashed()
    {
        $products = Product::onlyTrashed()
            ->with('tags')
            ->latest('deleted_at')
            ->paginate(12);

        return view(
            'products.trashed',
            compact('products')
        );
    }


    /**
     * ============================================================
     * TOGGLE STATUS
     * ============================================================
     */
    public function toggleStatus(Product $product)
    {
        $newStatus =
            $product->status === 'active'
            ? 'inactive'
            : 'active';

        $product->update([
            'status' => $newStatus
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Product status changed to ' . $newStatus . '.'
            );
    }


    /**
     * ============================================================
     * SELECT2 PRODUCT SEARCH
     * ============================================================
     */
    public function select2Search(Request $request)
    {
        $term = trim(
            $request->get('q', '')
        );

        $query = Product::with('tags');

        if ($term !== '') {

            $query->where(function ($q) use ($term) {

                $q->where(
                    'name',
                    'like',
                    '%' . $term . '%'
                )
                    ->orWhere(
                        'category',
                        'like',
                        '%' . $term . '%'
                    );
            });
        }

        $products = $query
            ->latest()
            ->take(20)
            ->get();

        return response()->json(
            $products->map(function ($product) {

                return [
                    'id' => $product->id,

                    'text' =>
                    $product->name .
                        ' - ' .
                        $product->category,
                ];
            })
        );
    }


    /**
     * ============================================================
     * CSV EXPORT
     *
     * Exports ONLY the currently filtered products.
     * ============================================================
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = Product::with('tags');

        $this->applyFilters(
            $query,
            $request
        );

        $fileName =
            'products_' .
            now()->format('Y-m-d_H-i-s') .
            '.csv';

        return response()->streamDownload(
            function () use ($query) {

                $handle = fopen(
                    'php://output',
                    'w'
                );

                /*
                |--------------------------------------------------------------------------
                | UTF-8 BOM
                |--------------------------------------------------------------------------
                */

                fprintf(
                    $handle,
                    chr(0xEF) .
                        chr(0xBB) .
                        chr(0xBF)
                );

                /*
                |--------------------------------------------------------------------------
                | CSV Header
                |--------------------------------------------------------------------------
                */

                fputcsv(
                    $handle,
                    [
                        'ID',
                        'Product Name',
                        'Category',
                        'Price',
                        'Size',
                        'Color',
                        'Status',
                        'Tags',
                        'Created At',
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Products
                |--------------------------------------------------------------------------
                */

                $query->chunk(
                    500,
                    function ($products) use ($handle) {

                        foreach ($products as $product) {

                            $tagNames =
                                $product
                                ->tags
                                ->pluck('tag_name')
                                ->implode(', ');

                            fputcsv(
                                $handle,
                                [
                                    $product->id,
                                    $product->name,
                                    $product->category,
                                    $product->price,
                                    $product->size,
                                    $product->color,
                                    ucfirst(
                                        $product->status
                                    ),
                                    $tagNames,
                                    optional(
                                        $product->created_at
                                    )->format(
                                        'Y-m-d H:i:s'
                                    ),
                                ]
                            );
                        }
                    }
                );

                fclose($handle);
            },
            $fileName,
            [
                'Content-Type' =>
                'text/csv; charset=UTF-8',
            ]
        );
    }


    /**
     * ============================================================
     * APPLY ADVANCED FILTERS
     *
     * Used by:
     * - index()
     * - exportCsv()
     * ============================================================
     */
    private function applyFilters(
        $query,
        Request $request
    ) {

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search =
                trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'details',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'category',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'color',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'size',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'price',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORY
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {

            $query->where(
                'category',
                $request->category
            );
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('status') &&
            in_array(
                $request->status,
                ['active', 'inactive']
            )
        ) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TAG
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tag')) {

            $query->whereHas(
                'tags',
                function ($tagQuery) use ($request) {

                    $tagQuery->where(
                        'tags.id',
                        $request->tag
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | MIN PRICE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('min_price')) {

            $query->where(
                'price',
                '>=',
                $request->min_price
            );
        }


        /*
        |--------------------------------------------------------------------------
        | MAX PRICE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('max_price')) {

            $query->where(
                'price',
                '<=',
                $request->max_price
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SORTING
        |--------------------------------------------------------------------------
        */

        $sort =
            $request->get(
                'sort',
                'latest'
            );

        switch ($sort) {

            case 'oldest':

                $query->orderBy(
                    'created_at',
                    'asc'
                );

                break;


            case 'name_asc':

                $query->orderBy(
                    'name',
                    'asc'
                );

                break;


            case 'name_desc':

                $query->orderBy(
                    'name',
                    'desc'
                );

                break;


            case 'price_low':

                $query->orderBy(
                    'price',
                    'asc'
                );

                break;


            case 'price_high':

                $query->orderBy(
                    'price',
                    'desc'
                );

                break;


            case 'category_asc':

                $query->orderBy(
                    'category',
                    'asc'
                );

                break;


            case 'category_desc':

                $query->orderBy(
                    'category',
                    'desc'
                );

                break;


            default:

                $query->latest();

                break;
        }

        return $query;
    }
}
