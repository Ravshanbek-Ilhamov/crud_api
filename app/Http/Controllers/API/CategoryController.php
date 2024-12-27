<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request as HttpRequest;

class CategoryController extends Controller
{
    public function index() {

        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    public function show(Category $category) {

        if (!$category) {
            return response()->json(['message' => 'Category not found']);
        }
        return new CategoryResource($category);
    }

    public function store(HttpRequest $request) {

        $request->validate([
            'name' => 'required',
        ]);

        $category = Category::create([
            'name' => $request->name
        ]);

        return new CategoryResource($category);
    }

    public function update(HttpRequest $request, Category $category) {

        $request->validate([
            'name' => 'required',
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return new CategoryResource($category);
    }

    public function posts(Category $category)
    {
        $data = [
            'posts' => $category->posts,
            'status' => 'success',
        ];
        return response()->json($data);
    }

    public function products(Category $category)
    {
        return ProductResource::collection($category->products);
    }

    public function destroy(Category $category) {

        if(!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully',
        ]);
    }
}
