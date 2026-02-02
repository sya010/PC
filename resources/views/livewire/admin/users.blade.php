<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
                Users Management
            </h1>
            <p class="text-gray-500 mt-1">Manage user accounts and administrative roles.</p>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white p-2 rounded-2xl border border-gray-100 shadow-sm mb-8 max-w-2xl transform transition-all hover:shadow-md">
        <div class="relative">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search users by name or email..." 
                maxlength="100"
                class="w-full pl-12 pr-4 py-3.5 rounded-xl border-none focus:ring-0 text-gray-900 placeholder-gray-400 bg-transparent"
            >
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                <div wire:loading wire:target="search" class="animate-spin rounded-full h-5 w-5 border-b-2 border-indigo-600"></div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    @if (session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl flex items-center gap-3 shadow-sm" role="alert">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50/50 text-xs uppercase font-bold text-gray-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Joined Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.view', $user->id) }}" wire:navigate class="flex items-center gap-4 group/user">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-700 font-bold text-lg shadow-sm border border-indigo-50 group-hover/user:scale-110 transition-transform">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 group-hover/user:text-indigo-600 transition-colors">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 font-medium">{{ $user->email }}</div>
                                    </div>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->is_admin)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700 border border-purple-200">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                        User
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-500">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" wire:navigate class="px-3 py-1.5 text-xs font-bold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 hover:text-gray-900 transition-colors">
                                        Edit
                                    </a>
                                    
                                    @if($user->is_admin)
                                        <button wire:click="demote({{ $user->id }})" wire:confirm="Are you sure you want to remove admin privileges from {{ $user->name }}?" class="px-3 py-1.5 text-xs font-bold text-yellow-700 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors">
                                            Demote
                                        </button>
                                    @else
                                        <button wire:click="promote({{ $user->id }})" wire:confirm="Are you sure you want to promote {{ $user->name }} to Admin?" class="px-3 py-1.5 text-xs font-bold text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 transition-colors">
                                            Make Admin
                                        </button>
                                    @endif
                                    
                                    <button wire:click="delete({{ $user->id }})" wire:confirm="Are you sure you want to delete this user?" class="p-1.5 text-gray-400 hover:text-rose-600 transition-colors hover:bg-rose-50 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-medium">
                                No users found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
            {{ $users->links() }}
        </div>
    </div>
</div>
