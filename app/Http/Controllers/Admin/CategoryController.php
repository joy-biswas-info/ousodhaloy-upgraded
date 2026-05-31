<?php
// ================================================================
// app/Http/Controllers/Admin/CategoryController.php
// ================================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['products' => fn($q) => $q->active()])->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'slug'       => 'nullable|string|unique:categories,slug',
            'icon'       => 'nullable|string|max:10',
            'sort_order' => 'nullable|integer',
        ]);

        Category::create([
            'name'        => $request->name,
            'slug'        => $request->slug ?: Str::slug($request->name),
            'icon'        => $request->icon ?? '💊',
            'description' => $request->description,
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Category created!');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $category->update([
            'name'        => $request->name,
            'icon'        => $request->icon ?? $category->icon,
            'description' => $request->description,
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => $request->boolean('is_active', true),
        ]);
        return back()->with('success', 'Category updated!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Cannot delete a category that has products.');
        }

        $category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
