<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::select('posts.id', 'posts.title', 'posts.description', 'categories.name as category_name')
            ->join('categories', 'posts.category_id', '=', 'categories.id')
            ->orderBy('id')->get();

        return response()->json($posts);
    }

    public function show(Post $post)
    {
        return response()->json([
            'post' => $post,
            'status' => 'success',
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'text' => 'required|string',
        ]);


        if ($request->hasFile('file_path')) {
            $validatedData['file_path'] = $request->file('file_path')->store('public');
        }

        $post = Post::create($validatedData);

        return response()->json([
            'post' => $post,
            'status' => 'success',
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'text' => 'required|string',
        ]);

        if ($request->hasFile('file_path')) {
            if ($post->file_path && Storage::exists($post->file_path)) {
                Storage::delete($post->file_path);
            }
            $validatedData['file_path'] = $request->file('file_path')->store('public');
        }

        $post->update($validatedData);

        return response()->json([
            'post' => $post,
            'status' => 'success',
        ]);
    }


    public function destroy(Post $post)
    {
        if ($post->file_path) {
            Storage::delete($post->file_path);
        }

        $post->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Post deleted successfully',
        ]);
    }

    public function categories(Post $post)
    {
        $category = $post->category;

        return response()->json([
            'category' => $category,
            'status' => 'success',
        ]);
    }
}
