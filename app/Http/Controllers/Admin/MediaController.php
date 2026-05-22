<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, Auth};

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::latest();

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('filename', 'like', "%{$search}%")
                    ->orWhere('original_name', 'like', "%{$search}%")
                    ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        if ($folder = $request->folder) {
            $query->where('folder', $folder);
        }

        $media = $query->paginate(40)->withQueryString();
        $folders = Media::distinct()->pluck('folder')->sort()->values();

        return view('admin.media.index', compact('media', 'folders'));
    }

    /**
     * Bulk upload — handles multiple files in one request.
     * Each file gets a SEO-friendly name.
     */
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array|min:1|max:50',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'folder' => 'nullable|string|max:50|regex:/^[a-z0-9_-]+$/',
            'alt_text' => 'nullable|string|max:200',
        ]);

        $folder = $request->input('folder', 'media');
        $altText = $request->input('alt_text', '');
        $uploaded = [];
        $errors = [];

        foreach ($request->file('files') as $file) {
            try {
                // SEO filename
                $seoName = Media::seoFilename($file->getClientOriginalName(), $folder);
                $path = $file->storeAs($folder, $seoName, 'public');

                // Get image dimensions
                $width = $height = null;
                try {
                    [$width, $height] = getimagesize($file->getRealPath());
                } catch (\Throwable $e) {
                }

                $media = Media::create([
                    'filename' => $seoName,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'width' => $width,
                    'height' => $height,
                    'alt_text' => $altText ?: pathinfo($seoName, PATHINFO_FILENAME),
                    'folder' => $folder,
                    'uploaded_by' => Auth::id(),
                ]);

                $uploaded[] = [
                    'id' => $media->id,
                    'filename' => $media->filename,
                    'url' => $media->url,
                    'size' => $media->human_size,
                ];
            } catch (\Throwable $e) {
                $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => count($uploaded) > 0,
                'uploaded' => $uploaded,
                'errors' => $errors,
                'message' => count($uploaded) . ' file(s) uploaded.' . (count($errors) ? ' ' . count($errors) . ' failed.' : ''),
            ]);
        }

        $msg = count($uploaded) . ' file(s) uploaded successfully.';
        if ($errors)
            $msg .= ' Failed: ' . implode('; ', $errors);

        return back()->with('success', $msg);
    }

    /**
     * Update alt text
     */
    public function update(Request $request, Media $medium)
    {
        $request->validate(['alt_text' => 'nullable|string|max:200']);
        $medium->update(['alt_text' => $request->alt_text]);
        return response()->json(['success' => true]);
    }

    /**
     * Delete file from disk and DB
     */
    public function destroy(Media $medium)
    {
        Storage::disk('public')->delete($medium->path);
        $medium->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'File deleted.');
    }

    /**
     * AJAX: search media for use in product forms / bulk import
     */
    public function search(Request $request)
    {
        $media = Media::when(
            $request->q,
            fn($query, $q) =>
            $query->where('filename', 'like', "%{$q}%")
                ->orWhere('original_name', 'like', "%{$q}%")
        )
            ->latest()
            ->take(30)
            ->get(['id', 'filename', 'original_name', 'path', 'alt_text', 'width', 'height'])
            ->map(fn($m) => [
                'id' => $m->id,
                'filename' => $m->filename,
                'name' => $m->original_name,
                'path' => $m->path,                        // storage-relative path for product thumbnail
                'url' => asset('storage/' . $m->path),   // full URL for <img src>
                'alt' => $m->alt_text ?? $m->filename,
            ]);

        return response()->json($media);
    }
}