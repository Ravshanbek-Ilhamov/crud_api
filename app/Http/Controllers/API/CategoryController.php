<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request as HttpRequest;

class CategoryController extends Controller
{
    public function index() {

        $categories = Category::with('posts')->get();

        $data = [
            'categories' => $categories,
            'status' => 'success',
        ];

        return response()->json($data);
    }

    public function show(Category $category) {

        if (!$category) {
            // abort(404);
            return response()->json(['message' => 'Category not found']);
        }

        $data = [
            'category' => $category,
            'status' => 'success',
        ];
        return response()->json($data);
    }

    public function store(HttpRequest $request) {

        $request->validate([
            'name' => 'required',
        ]);

        $category = Category::create([
            'name' => $request->name
        ]);

        $data = [
            'category' => $category,
            'status' => 'success',
        ];
        return response()->json($data);
    }

    public function update(HttpRequest $request, Category $category) {

        $request->validate([
            'name' => 'required',
        ]);

        $category->update([
            'name' => $request->name
        ]);

        $data = [
            'category' => $category,
            'status' => 'success',
        ];
        return response()->json($data);
    }

    public function posts(Category $category)
    {
        $data = [
            'posts' => $category->posts,
            'status' => 'success',
        ];
        return response()->json($data);
    }

    public function destroy(Category $category) {
        $category->delete();
        $data = [
            'status' => 'success',
        ];
        return response()->json($data);
    }
}
