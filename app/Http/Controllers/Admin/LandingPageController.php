<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{LandingPage, Media, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache, Storage};
use Illuminate\Validation\Rule;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $query = LandingPage::with('product')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($q = $request->q) {
            $query->where(fn($sub) => $sub->where('headline', 'like', "%{$q}%")
                ->orWhere('slug', 'like', "%{$q}%"));
        }

        $pages = $query->paginate(20)->withQueryString();

        // Order attribution counts, keyed by landing_page_id
        $orderCounts = \App\Models\Order::whereIn('landing_page_id', $pages->pluck('id'))
            ->selectRaw('landing_page_id, count(*) as c')
            ->groupBy('landing_page_id')
            ->pluck('c', 'landing_page_id');

        return view('admin.landing-pages.index', compact('pages', 'orderCounts'));
    }

    public function create()
    {
        $landingPage = null;
        $products = Product::active()->orderBy('name')->get(['id', 'name', 'sku']);
        return view('admin.landing-pages.form', compact('landingPage', 'products'));
    }

    public function store(Request $request)
    {
        $data = $this->validateAndPrepare($request);
        $data['created_by'] = auth()->id();

        $landingPage = LandingPage::create($data);
        $this->handleHeroImage($request, $landingPage);
        Cache::forget("landing_page:{$landingPage->slug}");

        return redirect()->route('admin.landing-pages.edit', $landingPage)
            ->with('success', 'Landing page created.');
    }

    public function edit(LandingPage $landingPage)
    {
        $products = Product::active()->orderBy('name')->get(['id', 'name', 'sku']);
        return view('admin.landing-pages.form', compact('landingPage', 'products'));
    }

    public function update(Request $request, LandingPage $landingPage)
    {
        $oldSlug = $landingPage->slug;
        $data = $this->validateAndPrepare($request, $landingPage->id);

        $landingPage->update($data);
        $this->handleHeroImage($request, $landingPage);

        Cache::forget("landing_page:{$oldSlug}");
        Cache::forget("landing_page:{$landingPage->slug}");

        return redirect()->route('admin.landing-pages.edit', $landingPage)
            ->with('success', 'Landing page updated.');
    }

    public function destroy(LandingPage $landingPage)
    {
        Cache::forget("landing_page:{$landingPage->slug}");
        $landingPage->delete();
        return redirect()->route('admin.landing-pages.index')->with('success', 'Landing page deleted.');
    }

    public function duplicate(LandingPage $landingPage)
    {
        $copy = $landingPage->replicate();
        $copy->slug = $this->uniqueSlug($landingPage->slug . '-copy');
        $copy->status = 'draft';
        $copy->views = 0;
        $copy->created_by = auth()->id();
        $copy->save();

        return redirect()->route('admin.landing-pages.edit', $copy)
            ->with('success', 'Duplicated as draft — edit and publish when ready.');
    }

    private function validateAndPrepare(Request $request, ?int $excludeId = null): array
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'slug' => [
                'required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('landing_pages', 'slug')->ignore($excludeId),
                function ($attr, $value, $fail) {
                    if (in_array($value, LandingPage::RESERVED_SLUGS, true)) {
                        $fail('That slug is reserved by the site and cannot be used.');
                    }
                },
            ],
            'status' => 'required|in:draft,published',
            'headline' => 'required|string|max:255',
            'subheadline' => 'nullable|string|max:255',
            'eyebrow_text' => 'nullable|string|max:100',
            'badge_text' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'countdown_end_at' => 'nullable|date',
            'theme' => 'nullable|json',
            'sections' => 'nullable|json',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'shipping_note' => 'nullable|string|max:1000',
            'return_policy_note' => 'nullable|string|max:1000',
        ], [
            'slug.regex' => 'Slug may only contain lowercase letters, numbers, and hyphens.',
        ]);

        $validated['theme'] = $validated['theme'] ?? null;
        $validated['sections'] = $validated['sections'] ?? null;

        return [
            ...$validated,
            'theme' => $validated['theme'] ? json_decode($validated['theme'], true) : LandingPage::defaultTheme(),
            'sections' => $validated['sections'] ? json_decode($validated['sections'], true) : LandingPage::defaultSections(),
        ];
    }

    private function handleHeroImage(Request $request, LandingPage $landingPage): void
    {
        if ($request->hasFile('hero_image_upload')) {
            $file = $request->file('hero_image_upload');
            $seoName = Media::seoFilename($file->getClientOriginalName(), 'landing-pages');
            $path = $file->storeAs('landing-pages', $seoName, 'public');

            [$width, $height] = @getimagesize($file->getRealPath()) ?: [null, null];
            Media::create([
                'filename' => $seoName,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'width' => $width,
                'height' => $height,
                'alt_text' => pathinfo($seoName, PATHINFO_FILENAME),
                'folder' => 'landing-pages',
                'uploaded_by' => auth()->id(),
            ]);
            $landingPage->update(['hero_image' => $path]);
        } elseif ($request->filled('hero_image_media_path')) {
            $landingPage->update(['hero_image' => $request->hero_image_media_path]);
        }
    }

    private function uniqueSlug(string $base): string
    {
        $slug = $base;
        $i = 1;
        while (LandingPage::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
