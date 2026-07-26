<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media_library';

    protected $fillable = [
        'filename',
        'original_name',
        'path',
        'mime_type',
        'size',
        'width',
        'height',
        'alt_text',
        'folder',
        'uploaded_by',
    ];

    protected $appends = ['url', 'human_size'];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // ── Accessors ─────────────────────────────────────────────────────────

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes < 1024)
            return $bytes . ' B';
        if ($bytes < 1048576)
            return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    // ── Static helpers ────────────────────────────────────────────────────

    /**
     * Generate SEO-friendly filename from original name.
     * "Napa 500mg Paracetamol Tablet.jpg" → "napa-500mg-paracetamol-tablet.jpg"
     * Handles duplicates by appending -2, -3 etc.
     */
    public static function seoFilename(string $originalName, string $folder = 'media'): string
    {
        $info = pathinfo($originalName);
        $ext = strtolower($info['extension'] ?? 'jpg');
        $base = Str::slug($info['filename']);           // slug removes non-ascii, lowercases
        $base = preg_replace('/-+/', '-', trim($base, '-'));
        $base = $base ?: 'image';

        // Ensure unique within folder
        $candidate = "{$base}.{$ext}";
        $counter = 2;
        while (Storage::disk('public')->exists("{$folder}/{$candidate}")) {
            $candidate = "{$base}-{$counter}.{$ext}";
            $counter++;
        }

        return $candidate;
    }

    /**
     * Find a media record by filename (for matching in bulk product CSV).
     */
    public static function findByFilename(string $filename): ?self
    {
        return static::where('filename', $filename)
            ->orWhere('original_name', $filename)
            ->first();
    }

    /**
     * Resize + re-encode an uploaded image as WebP and store it. Cuts typical
     * product/hero photo weight by 70-90% with no visible quality loss — this is
     * the single choke point (MediaController::store) that both the product form
     * and the landing-page hero picker upload through, so fixing it here means
     * every future upload is fast by default instead of needing a manual pass.
     * Not used for prescriptions — those must stay as the customer uploaded them.
     */
    public static function storeOptimizedImage(
        \Illuminate\Http\UploadedFile $file,
        string $folder = 'media',
        int $maxDimension = 1600,
        int $quality = 82
    ): array {
        $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'image';

        $candidate = "{$base}.webp";
        $counter = 2;
        while (Storage::disk('public')->exists("{$folder}/{$candidate}")) {
            $candidate = "{$base}-{$counter}.webp";
            $counter++;
        }

        $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $image = $manager->read($file->getRealPath());
        $image->scaleDown(width: $maxDimension, height: $maxDimension);
        $encoded = (string) $image->toWebp(quality: $quality);

        $path = "{$folder}/{$candidate}";
        Storage::disk('public')->put($path, $encoded);

        return [
            'filename' => $candidate,
            'path' => $path,
            'mime_type' => 'image/webp',
            'size' => strlen($encoded),
            'width' => $image->width(),
            'height' => $image->height(),
        ];
    }

    /**
     * Store an uploaded image, optimizing it unless it's a GIF (animated GIFs
     * would silently lose their animation if re-encoded to WebP here, so those
     * are stored as-is). Shared by every controller that accepts an image upload
     * so none of them have to duplicate the "except GIF" branch themselves.
     */
    public static function storeUploadedImage(\Illuminate\Http\UploadedFile $file, string $folder = 'media'): array
    {
        if (strtolower($file->getClientOriginalExtension()) === 'gif') {
            $seoName = static::seoFilename($file->getClientOriginalName(), $folder);
            $path = $file->storeAs($folder, $seoName, 'public');
            [$width, $height] = @getimagesize($file->getRealPath()) ?: [null, null];
            return [
                'filename' => $seoName, 'path' => $path, 'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(), 'width' => $width, 'height' => $height,
            ];
        }

        return static::storeOptimizedImage($file, $folder);
    }
}