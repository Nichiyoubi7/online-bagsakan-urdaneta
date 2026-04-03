<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount("products")->get();
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = Category::withCount("products")->findOrFail($id);
        return response()->json($category);
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255|unique:categories",
            "description" => "nullable|string",
        ]);
        $category = Category::create([
            "name" => $request->name,
            "slug" => \Str::slug($request->name),
            "description" => $request->description,
            "is_active" => true,
        ]);
        return response()->json(["message" => "Category created!", "category" => $category], 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            "name" => "sometimes|string|max:255|unique:categories,name,".$id,
        ]);
        $category->update($request->only(["name", "description", "is_active"]));
        return response()->json(["message" => "Category updated!", "category" => $category]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(["message" => "Category deleted!"]);
    }
}
