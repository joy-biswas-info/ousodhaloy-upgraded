@extends('layouts.admin')
@section('title', 'Landing Pages')
@section('page-title', 'Landing Pages')
@section('breadcrumb', 'Landing Pages')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between flex-wrap gap-3">
        <form method="GET" class="flex gap-2 flex-wrap">
            <input type="text" name="q" value="{{ request('q') }}" class="form-input w-56" placeholder="Search headline or slug…">
            <select name="status" class="form-select w-36">
                <option value="">All Status</option>
                <option value="draft" @selected(request('status')==='draft')>Draft</option>
                <option value="published" @selected(request('status')==='published')>Published</option>
            </select>
            <button type="submit" class="btn-primary btn-sm">Filter</button>
            <a href="{{ route('admin.landing-pages.index') }}" class="btn-outline btn-sm">Reset</a>
        </form>
        <a href="{{ route('admin.landing-pages.create') }}" class="btn-primary btn-sm">
            <i class="fas fa-plus mr-1.5"></i>New Landing Page
        </a>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Headline</th>
                        <th>Product</th>
                        <th>URL</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Orders</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                    <tr>
                        <td>
                            <p class="font-semibold text-sm text-gray-800">{{ Str::limit($page->headline, 40) }}</p>
                            <p class="text-xs text-gray-400">Updated {{ $page->updated_at?->diffForHumans() ?? '—' }}</p>
                        </td>
                        <td>
                            <span class="text-sm text-gray-600">{{ $page->product->name ?? '— deleted —' }}</span>
                        </td>
                        <td>
                            @if($page->status === 'published')
                            <a href="{{ url($page->slug) }}" target="_blank" class="text-xs text-teal-600 hover:underline">
                                /{{ $page->slug }} <i class="fas fa-external-link-alt text-[10px]"></i>
                            </a>
                            @else
                            <span class="text-xs text-gray-400">/{{ $page->slug }}</span>
                            @endif
                        </td>
                        <td>
                            @if($page->status === 'published')
                            <span class="text-xs font-semibold text-green-600">✅ Published</span>
                            @else
                            <span class="text-xs font-semibold text-yellow-600">⏳ Draft</span>
                            @endif
                        </td>
                        <td class="text-sm text-gray-600">{{ number_format($page->views) }}</td>
                        <td class="text-sm font-semibold text-gray-800">{{ $orderCounts[$page->id] ?? 0 }}</td>
                        <td>
                            <div class="flex gap-1.5 flex-wrap">
                                <a href="{{ route('admin.landing-pages.edit', $page) }}" class="btn-secondary btn-sm text-xs">
                                    <i class="fas fa-edit text-[10px]"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.landing-pages.duplicate', $page) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn-outline btn-sm text-xs">
                                        <i class="fas fa-copy text-[10px]"></i> Duplicate
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.landing-pages.destroy', $page) }}" class="inline"
                                    onsubmit="return confirm('Delete this landing page? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm text-xs">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            No landing pages yet — <a href="{{ route('admin.landing-pages.create') }}" class="text-teal-600 font-semibold">create your first one</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $pages->links() }}</div>
    </div>
</div>
@endsection
