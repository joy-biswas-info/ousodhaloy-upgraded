@extends('layouts.admin')
@section('page-title', 'Brands')
@include('partials.media-picker')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- ── Brand list ──────────────────────────────────────── --}}
    <div class="lg:col-span-2 bg-white rounded-xl border overflow-hidden">
        <div class="px-5 py-4 border-b flex items-center justify-between">
            <h2 class="font-bold text-gray-800">Brands <span class="text-gray-400 font-normal text-sm ml-1">({{ $brands->total() }})</span></h2>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Brand</th>
                    <th>Country</th>
                    <th class="text-center">Products</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr x-data="{ editing: false }">

                    {{-- View mode --}}
                    <td x-show="!editing">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-50 border flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if($brand->logo)
                                    <img src="{{ $brand->logo_url }}" class="max-h-full max-w-full object-contain">
                                @else
                                    <i class="fas fa-flask text-gray-300 text-sm"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-sm text-gray-800">{{ $brand->name }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $brand->slug }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Edit mode: spans brand + country + products columns --}}
                    <td colspan="3" x-show="editing" class="py-3 pr-3">
                        <form method="POST" action="{{ route('admin.brands.update', $brand) }}" class="flex flex-wrap gap-2 items-end" id="edit-form-{{ $brand->id }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="logo_media_path" id="logo-path-{{ $brand->id }}" value="{{ $brand->logo }}">

                            {{-- Logo picker --}}
                            <div class="flex-shrink-0">
                                <p class="text-xs text-gray-500 mb-1">Logo</p>
                                <div class="relative w-12 h-12 rounded-lg bg-gray-50 border overflow-hidden cursor-pointer group"
                                     onclick="pickBrandLogo({{ $brand->id }})">
                                    <img id="logo-preview-{{ $brand->id }}"
                                         src="{{ $brand->logo_url ?: '' }}"
                                         class="w-full h-full object-contain {{ $brand->logo ? '' : 'hidden' }}">
                                    <div id="logo-placeholder-{{ $brand->id }}" class="{{ $brand->logo ? 'hidden' : '' }} w-full h-full flex items-center justify-center">
                                        <i class="fas fa-flask text-gray-300 text-sm"></i>
                                    </div>
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <i class="fas fa-camera text-white text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Name</label>
                                <input type="text" name="name" value="{{ $brand->name }}" class="form-input" style="width:160px" required>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Country</label>
                                <input type="text" name="country" value="{{ $brand->country }}" class="form-input" style="width:110px" placeholder="Bangladesh">
                            </div>
                            <div class="flex gap-1.5 items-end">
                                <button type="submit" class="btn-primary btn-sm">Save</button>
                                <button type="button" @click="editing = false" class="btn-outline btn-sm">Cancel</button>
                            </div>
                        </form>
                    </td>

                    <td x-show="!editing" class="text-sm text-gray-500">{{ $brand->country ?? '—' }}</td>

                    <td x-show="!editing" class="text-center text-sm text-gray-600">{{ $brand->products_count }}</td>

                    <td x-show="!editing" class="text-center">
                        <span class="text-xs font-semibold {{ $brand->is_active ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $brand->is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </td>

                    <td class="text-right" x-show="!editing">
                        <div class="flex gap-1.5 justify-end">
                            <button @click="editing = true" class="btn-secondary btn-sm">Edit</button>

                            <form method="POST" action="{{ route('admin.brands.update', $brand) }}" x-ref="toggleForm" class="hidden">
                                @csrf @method('PUT')
                                <input type="hidden" name="name" value="{{ $brand->name }}">
                                <input type="hidden" name="country" value="{{ $brand->country }}">
                                <input type="hidden" name="is_active" value="{{ $brand->is_active ? 0 : 1 }}">
                            </form>
                            <button @click="$refs.toggleForm.submit()" class="btn-outline btn-sm">
                                {{ $brand->is_active ? 'Hide' : 'Show' }}
                            </button>

                            <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}"
                                  onsubmit="return confirm('Deactivate {{ addslashes($brand->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-gray-400">
                        <i class="fas fa-flask text-3xl mb-2 block"></i>
                        No brands yet — add one →
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t">{{ $brands->links() }}</div>
    </div>

    {{-- ── Add brand ────────────────────────────────────────── --}}
    <div class="space-y-5">
        <div class="bg-white rounded-xl border p-5">
            <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Add Brand</h2>
            <form method="POST" action="{{ route('admin.brands.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="logo_media_path" id="new-logo-path">

                {{-- Logo picker --}}
                <div>
                    <label class="form-label">Logo</label>
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden flex-shrink-0 cursor-pointer hover:border-teal-400 transition-colors"
                             onclick="pickNewBrandLogo()" id="new-logo-preview-wrap">
                            <img id="new-logo-preview" src="" class="w-full h-full object-contain hidden">
                            <i class="fas fa-flask text-gray-300 text-xl" id="new-logo-placeholder"></i>
                        </div>
                        <button type="button" onclick="pickNewBrandLogo()" class="btn-outline text-sm py-2 px-3">
                            <i class="fas fa-images mr-1.5"></i>Pick from library
                        </button>
                    </div>
                </div>

                <div>
                    <label class="form-label">Brand Name *</label>
                    <input type="text" name="name" class="form-input" placeholder="Square Pharmaceuticals" required>
                </div>
                <div>
                    <label class="form-label">Country</label>
                    <input type="text" name="country" value="Bangladesh" class="form-input">
                </div>
                <button type="submit" class="btn-primary w-full">Add Brand</button>
            </form>
        </div>

        {{-- Quick-add list --}}
        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs font-semibold text-gray-500 mb-3 uppercase tracking-wide">🇧🇩 Top BD Pharma</p>
            <div class="space-y-1">
                @foreach(['Square Pharmaceuticals','Beximco Pharma','Renata Limited','ACI Limited','Incepta Pharma','Drug International','Aristopharma','Eskayef','Opsonin Pharma','Globe Pharma'] as $b)
                <form method="POST" action="{{ route('admin.brands.store') }}">
                    @csrf
                    <input type="hidden" name="name" value="{{ $b }}">
                    <input type="hidden" name="country" value="Bangladesh">
                    <button type="submit" class="w-full text-left text-xs text-gray-600 hover:text-teal-700 hover:bg-teal-50 px-2 py-1.5 rounded-lg transition-colors flex items-center justify-between group">
                        <span>{{ $b }}</span>
                        <i class="fas fa-plus text-gray-300 group-hover:text-teal-500 text-xs"></i>
                    </button>
                </form>
                @endforeach
            </div>
        </div>
    </div>

</div>

<script>
// New brand logo picker
function pickNewBrandLogo() {
    openMediaPicker('new-brand-logo', function(path, url) {
        document.getElementById('new-logo-path').value = path;
        document.getElementById('new-logo-preview').src = url;
        document.getElementById('new-logo-preview').classList.remove('hidden');
        document.getElementById('new-logo-placeholder').classList.add('hidden');
    });
}

// Existing brand logo picker (in edit row)
function pickBrandLogo(id) {
    openMediaPicker('brand-logo-' + id, function(path, url) {
        document.getElementById('logo-path-' + id).value = path;
        var img = document.getElementById('logo-preview-' + id);
        var placeholder = document.getElementById('logo-placeholder-' + id);
        img.src = url;
        img.classList.remove('hidden');
        placeholder.classList.add('hidden');
    });
}
</script>
@endsection