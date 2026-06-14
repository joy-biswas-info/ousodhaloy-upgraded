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
        $request->validate(['name' => 'required|string|max:100']);

        $path = null;
        if ($request->filled('logo_media_path'))
            $path = $request->logo_media_path;
        elseif ($request->hasFile('logo'))
            $path = $request->file('logo')->store('brands', 'public');

        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'country' => $request->country,
            'logo' => $path,
            'is_active' => true,
        ]);
        return back()->with('success', 'Brand created!');
    }

    public function update(Request $request, Brand $brand)
    {
        $path = $brand->logo;
        if ($request->filled('logo_media_path'))
            $path = $request->logo_media_path;

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
