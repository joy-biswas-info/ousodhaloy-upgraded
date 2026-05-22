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
}