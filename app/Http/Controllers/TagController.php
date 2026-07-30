<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // Show all tags
    public function index()
    {
        $tags = Tag::latest()->paginate(10);
        return view('tags.index', compact('tags'));
    }

    // Show create form
    public function create()
    {
        return view('tags.create');
    }

    // Store new tag
    public function store(Request $request)
    {
        $request->validate([
            'tag_name' => 'required|string|max:255',
        ]);

        Tag::create([
            'tag_name' => $request->tag_name,
        ]);

        return redirect()->route('tags.index')->with('success', 'Tag created successfully!');
    }

    // Edit form
    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    // Update tag
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'tag_name' => 'required|string|max:255',
        ]);

        $tag->update([
            'tag_name' => $request->tag_name,
        ]);

        return redirect()->route('tags.index')->with('success', 'Tag updated successfully!');
    }

    // Delete tag
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tags.index')->with('success', 'Tag deleted successfully!');
    }

    public function select2Search(Request $request)
    {
        $term = $request->get('q', '');

        $query = Tag::query();

        if ($term) {
            $query->where('tag_name', 'like', '%' . $term . '%');
        }

        $tags = $query->latest()->take(20)->get();

        return response()->json($tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'text' => $tag->tag_name,
            ];
        }));
    }
}
