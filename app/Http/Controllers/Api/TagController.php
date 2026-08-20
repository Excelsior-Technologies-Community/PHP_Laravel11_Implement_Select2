<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Load tags for Select2.
     */
    public function select2(Request $request)
    {
        $search = $request->get('q', '');

        $tags = Tag::query()
            ->when($search, function ($query) use ($search) {
                $query->where('tag_name', 'like', '%' . $search . '%');
            })
            ->orderBy('tag_name')
            ->limit(20)
            ->get();

        return response()->json(
            $tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'text' => $tag->tag_name,
                ];
            })
        );
    }

    /**
     * Create a new tag dynamically.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tag_name' => [
                'required',
                'string',
                'max:100',
                'unique:tags,tag_name',
            ],
        ]);

        $tag = Tag::create([
            'tag_name' => trim($request->tag_name),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tag created successfully.',
            'data' => [
                'id' => $tag->id,
                'text' => $tag->tag_name,
            ],
        ], 201);
    }
}