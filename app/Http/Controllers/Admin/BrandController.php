<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index() {
        $brands = Brand::withCount('products')->orderBy('name')->paginate(30);
        return view('admin.brands.index', compact('brands'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,webp,gif|max:5120',
        ]);

        // Slug is derived from name (no slug field in the form), so a
        // collision isn't caught by validating $request fields — check the
        // derived value directly instead of letting the DB's unique
        // constraint surface as a raw 500 on INSERT.
        $slug = Str::slug($request->name);
        if (Brand::where('slug', $slug)->exists()) {
            return back()->withErrors(['name' => 'A brand with this name (or a very similar one) already exists.'])->withInput();
        }

        $path = null;
        if ($request->filled('logo_media_path'))
            $path = $request->logo_media_path;
        elseif ($request->hasFile('logo'))
            $path = $request->file('logo')->store('brands', 'public');

        Brand::create([
            'name' => $request->name,
            'slug' => $slug,
            'country' => $request->country,
            'logo' => $path,
            'is_active' => true,
        ]);
        return back()->with('success', 'Brand created!');
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,webp,gif|max:5120',
        ]);

        $slug = Str::slug($request->name);
        if (Brand::where('slug', $slug)->where('id', '!=', $brand->id)->exists()) {
            return back()->withErrors(['name' => 'A brand with this name (or a very similar one) already exists.'])->withInput();
        }

        $path = $brand->logo;
        if ($request->filled('logo_media_path'))
            $path = $request->logo_media_path;
        elseif ($request->hasFile('logo'))
            $path = $request->file('logo')->store('brands', 'public');

        $brand->update([
            'name' => $request->name,
            'country' => $request->country,
            'logo' => $path,
            'is_active' => $request->boolean('is_active', true),
        ]);
        return back()->with('success', 'Brand updated!');
    }
    public function destroy(Brand $brand)
    {
        $replacementBrand = Brand::where('id', '!=', $brand->id)
            ->orderBy('id')
            ->first();

        if (!$replacementBrand) {
            return back()->with('error', 'Cannot delete the last remaining brand.');
        }

        $brand->products()->update([
            'brand_id' => $replacementBrand->id,
        ]);

        $brand->delete();

        return back()->with(
            'success',
            "Brand deleted. Products moved to {$replacementBrand->name}."
        );
    }
}
