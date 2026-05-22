@extends('layouts.admin')
@section('page-title', 'Customers')

@section('content')
    <div class="space-y-4">

        {{-- Search --}}
        <div class="bg-white rounded-xl border p-4">
            <form method="GET" class="flex gap-3">
                <input type="text" name="q" value="{{ request('q') }}" class="form-input flex-1"
                    placeholder="Search by name, phone, or email...">
                <button type="submit" class="btn-primary btn-sm">Search</button>
                <a href="{{ route('admin.users.index') }}" class="btn-outline btn-sm">Reset</a>
            </form>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach([['label' => 'Total Customers', 'value' => \App\Models\User::where('role', 'customer')->count(), 'icon' => 'users', 'color' => 'blue'], ['label' => 'Phone Verified', 'value' => \App\Models\User::where('role', 'customer')->whereNotNull('phone_verified_at')->count(), 'icon' => 'mobile-alt', 'color' => 'green'], ['label' => 'Active Today', 'value' => \App\Models\User::where('updated_at', '>=', today())->count(), 'icon' => 'user-clock', 'color' => 'teal'], ['label' => 'Admins/Managers', 'value' => \App\Models\User::whereIn('role', ['admin', 'manager'])->count(), 'icon' => 'user-shield', 'color' => 'purple'],] as $card)
                <div class="bg-white rounded-xl border p-4 flex items-center gap-3">
                    <div class="w-9 h-9 bg-{{ $card['color'] }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-{{ $card['icon'] }} text-{{ $card['color'] }}-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">{{ $card['label'] }}</p>
                        <p class="text-xl font-black text-gray-800">{{ number_format($card['value']) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Orders</th>
                            <th>Joined</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-sm flex-shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-sm text-gray-800">{{ $user->name }}</p>
                                            @if($user->referral_code)
                                                <p class="text-[10px] text-gray-400 font-mono">{{ $user->referral_code }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-sm">
                                    {{ $user->phone ?? '—' }}
                                    @if($user->phone_verified_at)<i class="fas fa-check-circle text-green-500 text-xs ml-1"
                                    title="Verified"></i>@endif
                                </td>
                                <td>
                                    <span
                                        class="text-xs font-semibold px-2 py-0.5 rounded-full capitalize
                                        {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : ($user->role === 'manager' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.index', ['q' => $user->phone]) }}"
                                        class="font-bold text-teal-700 hover:underline">{{ $user->orders_count }}</a>
                                </td>
                                <td class="text-xs text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <span
                                        class="text-xs font-semibold {{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $user->is_active ? 'Active' : 'Suspended' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex gap-1.5 items-center flex-wrap" x-data="{ open: false }">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary btn-sm">View</a>
                                        <div class="relative">
                                            <button @click="open=!open"
                                                class="btn-outline btn-sm text-xs flex items-center gap-1">
                                                Actions <i class="fas fa-chevron-down text-[9px]"></i>
                                            </button>
                                            <div x-show="open" @click.away="open=false" x-cloak
                                                class="absolute right-0 bg-white border rounded-xl shadow-xl z-50 py-1 w-44 mt-1">
                                                @foreach(['customer' => 'Customer', 'manager' => 'Manager', 'admin' => 'Admin'] as $role => $roleLabel)
                                                            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                                                @csrf @method('PATCH')
                                                                <input type="hidden" name="role" value="{{ $role }}">
                                                                <input type="hidden" name="is_active"
                                                                    value="{{ $user->is_active ? '1' : '1' }}">
                                                                <button type="submit"
                                                                    class="w-full text-left px-4 py-2 text-xs hover:bg-teal-50 transition-colors flex items-center gap-2 {{ $user->role === $role ? 'text-teal-700 font-bold' : 'text-gray-700' }}">
                                                                    @if($user->role === $role)<i
                                                                    class="fas fa-check text-teal-600 w-3"></i>@else<span cl
                                                                      a                 ss="w-3"></span>@endif
                                                                    Set as {{ $roleLabel }}
                                                                </button>
                                                            </form>
                                                @endforeach
                                                <div class="border-t my-1"></div>
                                                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="role" value="{{ $user->role }}">
                                                    <input type="hidden" name="is_active"
                                                        value="{{ $user->is_active ? '0' : '1' }}">
                                                    <button type="submit"
                                                        class="w-full text-left px-4 py-2 text-xs hover:bg-red-50 transition-colors {{ $user->is_active ? 'text-red-600' : 'text-green-600' }}">
                                                        <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} w-3 mr-1"></i>
                                                        {{ $user->is_active ? 'Suspend' : 'Activate' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-400">No customers found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $users->withQueryString()->links() }}</div>
        </div>
    </div>
@endsection