@extends('layouts.admin')
@section('page-title', 'Bulk Add Products')
@section('breadcrumb', 'Products / Bulk Import')

@section('content')
    <div class="space-y-5" x-data="bulkProducts()">

        {{-- Tabs --}}
        <div class="bg-white rounded-xl border p-1.5 flex gap-1">
            <button @click="tab='form'" :class="tab === 'form' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                <i class="fas fa-table mr-1.5"></i>Spreadsheet Entry
            </button>
            <button @click="tab='csv'" :class="tab === 'csv' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                <i class="fas fa-file-csv mr-1.5"></i>CSV Upload
            </button>
        </div>

        {{-- ── Spreadsheet form tab ─────────────────────────────── --}}
        <div x-show="tab==='form'">
            <form method="POST" action="{{ route('admin.products.bulk-store') }}" id="bulk-form">
                @csrf

                <div class="bg-white rounded-xl border overflow-hidden">
                    <div class="px-5 py-4 border-b flex items-center justify-between">
                        <h2 class="font-bold text-gray-800">Add Multiple Products</h2>
                        <button type="button" @click="addRow()" class="btn-secondary btn-sm"><i
                                class="fas fa-plus mr-1"></i>Add Row</button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs" style="min-width:1100px">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th
                                        class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase tracking-wide w-8">
                                        #</th>
                                    <th class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase tracking-wide"
                                        style="min-width:200px">Name *</th>
                                    <th class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase tracking-wide"
                                        style="min-width:140px">Generic Name</th>
                                    <th class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase tracking-wide"
                                        style="min-width:120px">Brand</th>
                                    <th class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase tracking-wide"
                                        style="min-width:120px">Category</th>
                                    <th
                                        class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase tracking-wide w-24">
                                        Price *</th>
                                    <th
                                        class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase tracking-wide w-24">
                                        MRP</th>
                                    <th
                                        class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase tracking-wide w-20">
                                        Stock *</th>
                                    <th
                                        class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase tracking-wide w-20">
                                        Unit</th>
                                    <th
                                        class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase tracking-wide w-20">
                                        Strength</th>
                                    <th
                                        class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase tracking-wide w-20">
                                        Form</th>
                                    <th class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase tracking-wide"
                                        style="min-width:130px">Tags</th>
                                    <th class="px-3 py-2.5 text-left text-gray-500 font-semibold uppercase tracking-wide"
                                        style="min-width:140px">Thumbnail</th>
                                    <th
                                        class="px-3 py-2.5 text-center text-gray-500 font-semibold uppercase tracking-wide w-10">
                                        Rx</th>
                                    <th class="px-3 py-2.5 w-8"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, i) in rows" :key="i">
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-3 py-2 text-gray-400 text-center" x-text="i+1"></td>
                                        <td class="px-2 py-1.5">
                                            <input type="text" :name="'products[' + i + '][name]'" x-model="row.name"
                                                class="form-input py-1.5 text-xs" placeholder="Product name" required>
                                        </td>
                                        <td class="px-2 py-1.5">
                                            <input type="text" :name="'products[' + i + '][generic_name]'"
                                                x-model="row.generic_name" class="form-input py-1.5 text-xs"
                                                placeholder="e.g. Paracetamol">
                                        </td>
                                        <td class="px-2 py-1.5">
                                            <input type="text" :name="'products[' + i + '][brand]'" x-model="row.brand"
                                                list="brand-list" class="form-input py-1.5 text-xs"
                                                placeholder="Brand name">
                                        </td>
                                        <td class="px-2 py-1.5">
                                            <input type="text" :name="'products[' + i + '][category]'" x-model="row.category"
                                                list="category-list" class="form-input py-1.5 text-xs"
                                                placeholder="Category">
                                        </td>
                                        <td class="px-2 py-1.5">
                                            <input type="number" :name="'products[' + i + '][price]'" x-model="row.price"
                                                step="0.01" min="0" class="form-input py-1.5 text-xs" placeholder="0.00"
                                                required>
                                        </td>
                                        <td class="px-2 py-1.5">
                                            <input type="number" :name="'products[' + i + '][mrp]'" x-model="row.mrp"
                                                step="0.01" min="0" class="form-input py-1.5 text-xs" placeholder="0.00">
                                        </td>
                                        <td class="px-2 py-1.5">
                                            <input type="number" :name="'products[' + i + '][stock]'" x-model="row.stock"
                                                min="0" class="form-input py-1.5 text-xs" placeholder="0" required>
                                        </td>
                                        <td class="px-2 py-1.5">
                                            <select :name="'products[' + i + '][unit]'" x-model="row.unit"
                                                class="form-select py-1.5 text-xs">
                                                <option value="pcs">pcs</option>
                                                <option value="strip">strip</option>
                                                <option value="bottle">bottle</option>
                                                <option value="box">box</option>
                                                <option value="tube">tube</option>
                                                <option value="vial">vial</option>
                                                <option value="sachet">sachet</option>
                                            </select>
                                        </td>
                                        <td class="px-2 py-1.5">
                                            <input type="text" :name="'products[' + i + '][strength]'" x-model="row.strength"
                                                class="form-input py-1.5 text-xs" placeholder="500mg">
                                        </td>
                                        <td class="px-2 py-1.5">
                                            <input type="text" :name="'products[' + i + '][form]'" x-model="row.form"
                                                list="form-list" class="form-input py-1.5 text-xs" placeholder="Tablet">
                                        </td>
                                        <td class="px-2 py-1.5">
                                            <input type="text" :name="'products[' + i + '][tags]'" x-model="row.tags"
                                                class="form-input py-1.5 text-xs" placeholder="fever,pain">
                                        </td>
                                        <td class="px-2 py-1.5">
                                            <div class="flex items-center gap-1">
                                                <input type="text" :name="'products[' + i + '][thumbnail]'"
                                                    x-model="row.thumbnail" class="form-input py-1.5 text-xs"
                                                    style="width:110px" placeholder="filename.jpg" :title="row . thumbnail">
                                                <button type="button" @click="openMediaPicker(i)"
                                                    class="flex-shrink-0 w-6 h-6 bg-teal-100 hover:bg-teal-200 text-teal-700 rounded flex items-center justify-center text-xs transition-colors"
                                                    title="Browse media library">
                                                    🖼
                                                </button>
                                            </div>
                                            <div x-show="row.thumbnail"
                                                class="mt-1 w-8 h-8 bg-gray-100 rounded overflow-hidden border">
                                                <img :src="row . thumbnailUrl || ''" class="w-full h-full object-cover"
                                                    onerror="this.style.display='none'">
                                            </div>
                                        </td>
                                        <td class="px-2 py-1.5 text-center">
                                            <input type="checkbox" :name="'products[' + i + '][requires_prescription]'"
                                                value="1" x-model="row.rx" class="accent-teal-600">
                                        </td>
                                        <td class="px-2 py-1.5 text-center">
                                            <button type="button" @click="removeRow(i)" x-show="rows.length > 1"
                                                class="text-red-400 hover:text-red-600 transition-colors">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="px-5 py-4 border-t flex items-center justify-between bg-gray-50">
                        <div class="flex gap-2">
                            <button type="button" @click="addRow()" class="btn-outline btn-sm">
                                <i class="fas fa-plus mr-1"></i>Add Row
                            </button>
                            <button type="button" @click="addRows(5)" class="btn-outline btn-sm">
                                +5 Rows
                            </button>
                            <button type="button" @click="clearAll()" class="btn-outline btn-sm text-red-500">
                                Clear All
                            </button>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-500" x-text="rows.length + ' rows'"></span>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Save All Products
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Datalists for autocomplete --}}
            <datalist id="brand-list">
                @foreach($brands as $b)<option value="{{ $b->name }}">@endforeach
            </datalist>
            <datalist id="category-list">
                @foreach($categories as $c)<option value="{{ $c->name }}">@endforeach
            </datalist>
            <datalist id="form-list">
                @foreach(['Tablet', 'Capsule', 'Syrup', 'Cream', 'Ointment', 'Injection', 'Eye Drop', 'Ear Drop', 'Inhaler', 'Suspension', 'Powder', 'Gel', 'Spray', 'Patch', 'Suppository'] as $f)
                    <option value="{{ $f }}">
                @endforeach
            </datalist>
        </div>

        {{-- ── CSV upload tab ───────────────────────────────────── --}}
        <div x-show="tab==='csv'">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                {{-- Upload --}}
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Upload CSV File</h2>
                    <form method="POST" action="{{ route('admin.products.import-csv') }}" enctype="multipart/form-data"
                        class="space-y-4">
                        @csrf
                        <div>
                            <label class="form-label">CSV File *</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-teal-400 transition-colors cursor-pointer"
                                onclick="document.getElementById('csv-input').click()">
                                <i class="fas fa-file-csv text-4xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600 font-semibold">Click to upload CSV</p>
                                <p class="text-xs text-gray-400 mt-1">Max 5MB</p>
                                <p id="csv-filename" class="text-xs text-teal-600 font-semibold mt-2"></p>
                            </div>
                            <input type="file" id="csv-input" name="csv_file" accept=".csv,.txt" class="hidden"
                                onchange="document.getElementById('csv-filename').textContent = this.files[0]?.name || ''">
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-xs text-blue-700 space-y-1">
                            <p class="font-bold">CSV Import notes:</p>
                            <p>• First row must be the header row</p>
                            <p>• Brand & category will be auto-created if they don't exist</p>
                            <p>• Products are matched by name or SKU — existing ones are updated</p>
                            <p>• <code>requires_prescription</code>: use 1 or 0</p>
                            <p>• Tags: comma-separated within the cell (e.g. <code>fever,pain</code>)</p>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="btn-primary flex-1">
                                <i class="fas fa-upload mr-2"></i>Import CSV
                            </button>
                            <a href="{{ route('admin.products.csv-template') }}" class="btn-outline flex-1 text-center">
                                <i class="fas fa-download mr-1"></i>Download Template
                            </a>
                        </div>
                    </form>
                </div>

                {{-- CSV format guide --}}
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">CSV Column Reference</h2>
                    <div class="overflow-x-auto">
                        <table class="admin-table text-xs">
                            <thead>
                                <tr>
                                    <th>Column</th>
                                    <th>Required</th>
                                    <th>Example</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach([['name', 'Yes', 'Napa 500mg'], ['generic_name', 'No', 'Paracetamol'], ['sku', 'No', 'NAP500'], ['brand', 'No', 'Beximco Pharma'], ['category', 'No', 'Medicine'], ['price', 'Yes', '0.90'], ['mrp', 'No', '1.00'], ['stock', 'Yes', '500'], ['unit', 'No', 'strip'], ['strength', 'No', '500mg'], ['form', 'No', 'Tablet'], ['pack_size', 'No', '10 tablets/strip'], ['requires_prescription', 'No', '0 or 1'], ['is_featured', 'No', '0 or 1'], ['tags', 'No', 'fever,pain'], ['description', 'No', 'Product description text'],] as $col)
                                    <tr>
                                        <td class="font-mono font-bold text-teal-700">{{ $col[0] }}</td>
                                        <td>
                                            <span
                                                class="text-xs font-semibold {{ $col[1] === 'Yes' ? 'text-red-600' : 'text-gray-400' }}">
                                                {{ $col[1] }}
                                            </span>
                                        </td>
                                        <td class="text-gray-500">{{ $col[2] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function bulkProducts() {
            return {
                tab: 'form',
                rows: [newRow()],

                addRow() { this.rows.push(newRow()); },
                addRows(n) { for (let i = 0; i < n; i++) this.rows.push(newRow()); },
                removeRow(i) { if (this.rows.length > 1) this.rows.splice(i, 1); },
                clearAll() { if (confirm('Clear all rows?')) this.rows = [newRow()]; },

                openMediaPicker(rowIndex) {
                    window._mediaPickerTarget = rowIndex;
                    document.getElementById('media-picker-modal').classList.remove('hidden');
                    document.getElementById('media-picker-modal').classList.add('flex');
                    loadMediaPicker('');
                },
            };
        }

        function newRow() {
            return { name: '', generic_name: '', brand: '', category: '', price: '', mrp: '', stock: '', unit: 'strip', strength: '', form: '', tags: '', thumbnail: '', thumbnailUrl: '', rx: false };
        }
    </script>
@endpush

{{-- ── Media Picker Modal ──────────────────────────────────────────── --}}
<div id="media-picker-modal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[80vh] flex flex-col">

        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="font-bold text-gray-800">🖼 Media Library — Pick Image</h3>
            <button onclick="closeMediaPicker()" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>

        <div class="p-4 border-b">
            <input type="text" id="media-search" placeholder="Search by filename…" oninput="loadMediaPicker(this.value)"
                class="form-input">
        </div>

        <div id="media-picker-grid" class="flex-1 overflow-y-auto p-4 grid grid-cols-4 sm:grid-cols-6 gap-3">
            <div class="col-span-6 text-center text-gray-400 py-8">
                <i class="fas fa-spinner fa-spin text-2xl"></i>
            </div>
        </div>

        <div class="p-3 border-t flex justify-between items-center">
            <a href="{{ route('admin.media.index') }}" target="_blank"
                class="text-xs text-teal-600 hover:underline font-semibold">
                <i class="fas fa-external-link-alt mr-1"></i>Open full Media Library
            </a>
            <button onclick="closeMediaPicker()" class="btn-outline btn-sm">Cancel</button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let _debounce;
        function loadMediaPicker(query) {
            clearTimeout(_debounce);
            _debounce = setTimeout(async () => {
                const grid = document.getElementById('media-picker-grid');
                grid.innerHTML = '<div class="col-span-6 text-center text-gray-400 py-8"><i class="fas fa-spinner fa-spin text-2xl"></i></div>';

                const res = await fetch(`/admin/media/search?q=${encodeURIComponent(query)}`);
                const data = await res.json();

                if (!data.length) {
                    grid.innerHTML = '<div class="col-span-6 text-center text-gray-400 py-8">No images found. <a href="{{ route('admin.media.index') }}" target="_blank" class="text-teal-600 underline">Upload some →</a></div>';
                    return;
                }

                grid.innerHTML = data.map(m => `
                <div onclick="pickMedia('${m.filename}', '${m.url}')"
                    class="cursor-pointer rounded-xl overflow-hidden border-2 border-transparent hover:border-teal-500 transition-colors group">
                    <div class="aspect-square bg-gray-50 overflow-hidden">
                        <img src="${m.url}" alt="${m.alt}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                    </div>
                    <div class="p-1">
                        <p class="text-[10px] text-gray-500 truncate">${m.filename}</p>
                    </div>
                </div>
            `).join('');
            }, 250);
        }

        function pickMedia(filename, url) {
            const idx = window._mediaPickerTarget;
            const comp = document.querySelector('[x-data]')?.__x?.$data;
            if (comp && typeof idx === 'number') {
                comp.rows[idx].thumbnail = filename;
                comp.rows[idx].thumbnailUrl = url;
            }
            closeMediaPicker();
        }

        function closeMediaPicker() {
            const m = document.getElementById('media-picker-modal');
            m.classList.add('hidden');
            m.classList.remove('flex');
            document.getElementById('media-search').value = '';
        }
    </script>
@endpush