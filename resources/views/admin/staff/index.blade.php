@extends('layouts.admin')
@section('page-title', 'Staff Management')
@section('breadcrumb', 'Staff')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Staff list --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Current staff --}}
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="px-5 py-4 border-b flex items-center justify-between">
                    <p class="font-bold text-gray-800">Team Members ({{ $staff->count() }})</p>
                </div>

                @forelse($staff as $member)
                    <div class="px-5 py-4 border-b last:border-0 flex items-center gap-4" x-data="{ editing: false }">

                        {{-- Avatar --}}
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                            style="background:var(--teal)">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0" x-show="!editing">
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-gray-800 text-sm">{{ $member->name }}</p>
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $member->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-teal-100 text-teal-700' }}">
                                    {{ ucfirst($member->role) }}
                                </span>
                                @if(!$member->is_active)
                                    <span
                                        class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-600">Suspended</span>
                                @endif
                                @if($member->id === auth()->id())
                                    <span
                                        class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">You</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $member->email }}
                                {{ $member->phone ? '· ' . $member->phone : '' }}</p>
                        </div>

                        {{-- Edit form (inline) --}}
                        <form method="POST" action="{{ route('admin.staff.update', $member) }}" x-show="editing" x-cloak
                            class="flex-1 grid grid-cols-2 gap-2">
                            @csrf @method('PATCH')
                            <input type="text" name="name" value="{{ $member->name }}" class="form-input col-span-2"
                                placeholder="Full name" required>
                            <input type="email" name="email" value="{{ $member->email }}" class="form-input" placeholder="Email"
                                required>
                            <input type="text" name="phone" value="{{ $member->phone }}" class="form-input" placeholder="Phone">
                            <input type="password" name="password" class="form-input"
                                placeholder="New password (leave blank to keep)">
                            <select name="role" class="form-select">
                                <option value="manager" @selected($member->role === 'manager')>Manager</option>
                                <option value="admin" @selected($member->role === 'admin')>Admin</option>
                            </select>
                            <label class="flex items-center gap-2 col-span-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ $member->is_active ? 'checked' : '' }}
                                    class="accent-teal-600">
                                <span class="text-sm text-gray-700">Active</span>
                            </label>
                            <div class="col-span-2 flex gap-2">
                                <button type="submit" class="btn-primary btn-sm flex-1">Save</button>
                                <button type="button" @click="editing=false" class="btn-outline btn-sm flex-1">Cancel</button>
                            </div>
                        </form>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 flex-shrink-0" x-show="!editing">
                            <button @click="editing=true" class="btn-outline btn-sm">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            @if($member->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.staff.destroy', $member) }}"
                                    onsubmit="return confirm('Remove {{ $member->name }} from staff?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-gray-400 text-sm">
                        No staff members yet. Add one →
                    </div>
                @endforelse
            </div>

            {{-- Role explanation --}}
            <div class="bg-white rounded-xl border p-5">
                <p class="text-sm font-bold text-gray-700 mb-3">Role Permissions</p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 rounded-xl" style="background:var(--teal-bg)">
                        <p class="text-sm font-bold mb-1" style="color:var(--teal)">Manager</p>
                        <ul class="text-xs text-gray-600 space-y-1">
                            <li>✓ View & manage orders</li>
                            <li>✓ Update order status</li>
                            <li>✓ Manage products</li>
                            <li>✓ View customers</li>
                            <li>✗ Delete products</li>
                            <li>✗ Manage staff</li>
                            <li>✗ Change settings</li>
                        </ul>
                    </div>
                    <div class="p-3 rounded-xl bg-purple-50">
                        <p class="text-sm font-bold text-purple-700 mb-1">Admin</p>
                        <ul class="text-xs text-gray-600 space-y-1">
                            <li>✓ Everything Manager can do</li>
                            <li>✓ Delete & restore products</li>
                            <li>✓ Manage staff accounts</li>
                            <li>✓ Change all settings</li>
                            <li>✓ View financial reports</li>
                            <li>✓ Push to couriers</li>
                            <li>✓ Full system access</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add new staff --}}
        <div class="bg-white rounded-xl border p-5 h-fit">
            <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b">Add Staff Member</h3>
            <form method="POST" action="{{ route('admin.staff.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-input" placeholder="e.g. Rahim Ahmed" required
                        value="{{ old('name') }}">
                    @error('name')         <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-input" placeholder="staff@ousodhaloy.com" required
                        value="{{ old('email') }}">
                    @error('email')              <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-input" placeholder="01XXXXXXXXX" value="{{ old('phone') }}">
                    @error('phone')      <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-select" required>
                        <option value="manager" selected>Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-input" placeholder="Min 8 characters" required>
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-user-plus mr-2"></i> Add Staff Member
                </button>
            </form>
        </div>
    </div>
@endsection