<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $posts = BlogPost::when($request->query('category'), fn($q, $cat) => $q->where('category', $cat))
            ->when($request->query('search'), function ($q, $term) {
                $q->where(function ($query) use ($term) {
                    $query->where('title', 'like', "%{$term}%")
                          ->orWhere('excerpt', 'like', "%{$term}%");
                });
            })
            ->latest('published_at')
            ->paginate(9);

        return response()->json($posts);
    }

    public function show(BlogPost $blog)
    {
        $blog->increment('views');
        return response()->json($blog);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'body' => 'required|string',
            'image' => 'nullable|image',
            'published_at' => 'nullable|date',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);
        $data['author_id'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blog', 'public');
        }

        $post = BlogPost::create($data);

        return response()->json($post, 201);
    }

    public function update(Request $request, BlogPost $blog)
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'category' => 'sometimes|nullable|string|max:255',
            'excerpt' => 'sometimes|nullable|string|max:500',
            'body' => 'sometimes|string',
            'image' => 'sometimes|nullable|image',
            'published_at' => 'sometimes|nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blog', 'public');
        }

        $blog->update($data);

        return response()->json($blog);
    }

    public function destroy(Request $request, BlogPost $blog)
    {
        $this->authorizeAdmin($request);
        $blog->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function rate(Request $request, BlogPost $blog)
    {
        $value = $request->validate(['rating' => 'required|numeric|min:1|max:5'])['rating'];

        // simple average calculation
        $blog->rating = ($blog->rating * $blog->views + $value) / ($blog->views + 1);
        $blog->views += 1;
        $blog->save();

        return response()->json(['rating' => $blog->rating]);
    }

    private function authorizeAdmin(Request $request): void
    {
        if ($request->user()->role !== 'admin') {
            abort(403);
        }
    }
}