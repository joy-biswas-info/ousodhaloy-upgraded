@extends('layouts.admin')
@section('page-title', 'Media Library')
@section('breadcrumb', 'Media Library')

@section('content')
    <div class="space-y-5" x-data="mediaLibrary()">

        {{-- Upload zone + toolbar --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-gray-800">Upload Images</h2>
                    <span class="text-xs text-gray-400">Max 50 files · 5MB each · JPG, PNG, WebP, GIF</span>
                </div>
                <div class="flex gap-2">
                    <select x-model="folder" class="form-select text-sm w-36">
                        <option value="media">📁 media</option>
                        <option value="products">📁 products</option>
                        <option value="banners">📁 banners</option>
                        <option value="brands">📁 brands</option>
                    </select>
                    <input type="text" x-model="altText" placeholder="Alt text for all uploads"
                        class="form-input text-sm w-52">
                </div>
            </div>

            {{-- Drop zone --}}
            <div id="drop-zone"
                class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-colors cursor-pointer hover:border-teal-400 hover:bg-teal-50"
                @dragover.prevent="$el.classList.add('border-teal-500','bg-teal-50')"
                @dragleave="$el.classList.remove('border-teal-500','bg-teal-50')" @drop.prevent="handleDrop($event)"
                @click="$refs.fileInput.click()">
                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3 block"></i>
                <p class="font-semibold text-gray-700">Drop images here or click to browse</p>
                <p class="text-xs text-gray-400 mt-1">Names are automatically converted to SEO-friendly format</p>
                <p class="text-xs text-teal-600 font-semibold mt-1">e.g. "Napa 500mg.jpg" → <code>napa-500mg.jpg</code></p>
            </div>
            <input type="file" x-ref="fileInput" multiple accept="image/*" class="hidden"
                @change="handleFiles($event.target.files)">

            {{-- Upload queue --}}
            <div x-show="queue.length > 0" class="mt-4 space-y-2">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700" x-text="queue.length + ' file(s) queued'"></span>
                    <div class="flex gap-2">
                        <button @click="uploadAll()" :disabled="uploading" class="btn-primary btn-sm">
                            <span x-show="!uploading"><i class="fas fa-upload mr-1"></i>Upload All</span>
                            <span x-show="uploading"><i class="fas fa-spinner fa-spin mr-1"></i>Uploading…</span>
                        </button>
                        <button @click="queue = []" :disabled="uploading" class="btn-outline btn-sm">Clear</button>
                    </div>
                </div>

                {{-- Queue preview --}}
                <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto">
                    <template x-for="(item, i) in queue" :key="i">
                        <div class="relative w-16 h-16 rounded-lg overflow-hidden border bg-gray-50 flex-shrink-0">
                            <img :src="item . preview" class="w-full h-full object-cover">
                            <div x-show="item.status === 'uploading'"
                                class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                <i class="fas fa-spinner fa-spin text-white text-sm"></i>
                            </div>
                            <div x-show="item.status === 'done'"
                                class="absolute inset-0 bg-green-500/70 flex items-center justify-center">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <div x-show="item.status === 'error'"
                                class="absolute inset-0 bg-red-500/70 flex items-center justify-center">
                                <i class="fas fa-times text-white text-sm"></i>
                            </div>
                            <button @click="queue.splice(i,1)" x-show="!item.status"
                                class="absolute top-0 right-0 bg-red-500 text-white w-4 h-4 flex items-center justify-center text-[10px] rounded-bl">
                                ×
                            </button>
                            <div class="absolute bottom-0 left-0 right-0 bg-black/60 px-1 py-0.5">
                                <p class="text-[9px] text-white truncate" x-text="item.seoName"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Result messages --}}
            <div x-show="resultMsg" class="mt-3 text-sm font-semibold" :class="resultError ? 'text-red-600' : 'text-green-600'" x-text="resultMsg"></div>
        </div>

        {{-- Filters + search --}}
        <div class="bg-white rounded-xl border p-4">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="q" value="{{ request('q') }}" class="form-input flex-1 min-w-48"
                    placeholder="Search by filename or alt text…">
                <select name="folder" class="form-select w-36">
                    <option value="">All folders</option>
                    @foreach($folders as $f)
                        <option value="{{ $f }}" @selected(request('folder') === $f)>📁 {{ $f }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary btn-sm">Filter</button>
                <a href="{{ route('admin.media.index') }}" class="btn-outline btn-sm">Reset</a>
                <span class="text-xs text-gray-400 ml-auto">{{ $media->total() }} files</span>
            </form>
        </div>

        {{-- Media grid --}}
        @if($media->isEmpty())
            <div class="bg-white rounded-xl border p-16 text-center text-gray-400">
                <i class="fas fa-images text-5xl mb-3 block opacity-30"></i>
                <p class="font-semibold">No media files yet</p>
                <p class="text-sm mt-1">Upload some images above</p>
            </div>
        @else
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                @foreach($media as $item)
                    <div class="group relative bg-white rounded-xl border overflow-hidden hover:shadow-md transition-shadow"
                        x-data="{ showInfo: false }">
                        {{-- Thumbnail --}}
                        <div class="aspect-square bg-gray-50 flex items-center justify-center overflow-hidden cursor-pointer"
                            @click="showInfo = !showInfo">
                            <img src="{{ $item->url }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover"
                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div class="hidden w-full h-full items-center justify-center text-gray-300">
                                <i class="fas fa-image text-2xl"></i>
                            </div>
                        </div>

                        {{-- Hover overlay --}}
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors pointer-events-none">
                        </div>

                        {{-- Action buttons --}}
                        <div class="absolute top-1 right-1 hidden group-hover:flex gap-1">
                            {{-- Copy URL --}}
                            <button onclick="copyUrl('{{ $item->url }}', this)" title="Copy URL"
                                class="w-6 h-6 bg-white/90 hover:bg-white rounded text-gray-700 text-xs flex items-center justify-center shadow transition-colors">
                                <i class="fas fa-copy"></i>
                            </button>
                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.media.destroy', $item) }}" class="inline"
                                onsubmit="return confirm('Delete {{ $item->filename }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Delete"
                                    class="w-6 h-6 bg-red-500/90 hover:bg-red-600 rounded text-white text-xs flex items-center justify-center shadow transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>

                        {{-- Info panel --}}
                        <div x-show="showInfo" @click.away="showInfo = false" x-cloak
                            class="absolute inset-0 bg-white/98 p-2 text-xs overflow-y-auto z-10">
                            <p class="font-bold text-gray-800 break-all mb-1">{{ $item->filename }}</p>
                            <p class="text-gray-500 break-all mb-1 text-[10px]">{{ $item->original_name }}</p>
                            <p class="text-gray-400 text-[10px]">{{ $item->human_size }}
                                @if($item->width) · {{ $item->width }}×{{ $item->height }}@endif
                            </p>
                            {{-- Editable alt text --}}
                            <input type="text" value="{{ $item->alt_text }}" placeholder="Alt text…"
                                class="form-input text-xs mt-1 py-1" @change="updateAlt({{ $item->id }}, $event.target.value)">
                            {{-- Copy filename for CSV --}}
                            <button onclick="copyUrl('{{ $item->filename }}', this)"
                                class="w-full mt-1 bg-teal-50 hover:bg-teal-100 border border-teal-200 text-teal-700 text-[10px] font-semibold px-2 py-1 rounded transition-colors">
                                📋 Copy filename for CSV
                            </button>
                            <button onclick="copyUrl('{{ $item->url }}', this)"
                                class="w-full mt-1 bg-gray-50 hover:bg-gray-100 border text-gray-600 text-[10px] font-semibold px-2 py-1 rounded transition-colors">
                                🔗 Copy full URL
                            </button>
                        </div>

                        {{-- Filename label --}}
                        <div class="p-1.5 border-t">
                            <p class="text-[10px] text-gray-500 truncate" title="{{ $item->filename }}">{{ $item->filename }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-2">{{ $media->withQueryString()->links() }}</div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        const CSRF = document.querySelector('meta[name=csrf-token]').content;

        function mediaLibrary() {
            return {
                folder: 'media',
                altText: '',
                queue: [],
                uploading: false,
                resultMsg: '',
                resultError: false,

                // Slugify filename client-side so user sees the SEO name before upload
                slugifyName(originalName) {
                    const ext = originalName.split('.').pop().toLowerCase();
                    const base = originalName.replace(/\.[^/.]+$/, '')  // remove ext
                        .toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .trim()
                        .replace(/[\s_]+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-|-$/g, '') || 'image';
                    return `${base}.${ext}`;
                },

                handleFiles(files) {
                    for (const file of files) {
                        if (!file.type.startsWith('image/')) continue;
                        const reader = new FileReader();
                        reader.onload = e => {
                            this.queue.push({
                                file,
                                preview: e.target.result,
                                seoName: this.slugifyName(file.name),
                                status: null,
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                },

                handleDrop(event) {
                    event.currentTarget.classList.remove('border-teal-500', 'bg-teal-50');
                    this.handleFiles(event.dataTransfer.files);
                },

                async uploadAll() {
                    if (!this.queue.length || this.uploading) return;
                    this.uploading = true;
                    this.resultMsg = '';

                    // Upload in batches of 10
                    const batchSize = 10;
                    let totalUploaded = 0, totalErrors = 0;

                    for (let i = 0; i < this.queue.length; i += batchSize) {
                        const batch = this.queue.slice(i, i + batchSize);
                        const fd = new FormData();
                        fd.append('folder', this.folder);
                        fd.append('alt_text', this.altText);
                        batch.forEach((item, j) => {
                            fd.append('files[]', item.file);
                            item.status = 'uploading';
                        });

                        try {
                            const res = await fetch('{{ route('admin.media.store') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': CSRF,
                                    'Accept': 'application/json',
                                },
                                body: fd,
                            });

                            if (!res.ok && res.status !== 422) {
                                throw new Error('Server error ' + res.status);
                            }

                            const data = await res.json();

                            if (data.success === false && data.errors) {
                                // Laravel validation errors
                                batch.forEach(item => { item.status = 'error'; totalErrors++; });
                            } else {
                                batch.forEach((item, j) => {
                                    if (data.uploaded?.[j]) {
                                        item.status = 'done';
                                        item.seoName = data.uploaded[j].filename;
                                        totalUploaded++;
                                    } else {
                                        item.status = 'error';
                                        totalErrors++;
                                    }
                                });
                            }
                        } catch (e) {
                            batch.forEach(item => { item.status = 'error'; totalErrors++; });
                        }
                    }

                    this.uploading = false;
                    this.resultError = totalErrors > 0 && totalUploaded === 0;
                    this.resultMsg = `${totalUploaded} uploaded${totalErrors ? ', ' + totalErrors + ' failed' : ''}. Refreshing…`;

                    // Reload after a moment to show new files in grid
                    setTimeout(() => location.reload(), 1200);
                },
            };
        }

        function copyUrl(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const orig = btn.innerHTML;
                btn.innerHTML = '✅';
                btn.classList.add('text-green-600');
                setTimeout(() => { btn.innerHTML = orig; btn.classList.remove('text-green-600'); }, 1500);
            });
        }

        async function updateAlt(id, altText) {
            await fetch(`/admin/media/${id}`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ alt_text: altText }),
            });
        }
    </script>
@endpush