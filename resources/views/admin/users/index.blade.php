<x-app-layout>
    <x-slot name="header">User Management</x-slot>

    <div class="mt-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">Registered Users</h2>
                <a href="{{ route('admin.users.create') }}"
                   class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New User
                </a>
            </div>
            @if($users->isEmpty())
                <p class="px-6 py-10 text-center text-sm text-gray-400">No users found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Name</th>
                                <th class="px-6 py-3 text-left">Email</th>
                                <th class="px-6 py-3 text-left">Role</th>
                                <th class="px-6 py-3 text-left">Registered</th>
                                <th class="px-6 py-3 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 text-xs font-bold shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-gray-800">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-gray-600">{{ $user->email }}</td>
                                    <td class="px-6 py-3">
                                        @if($user->roles->isEmpty())
                                            <span class="px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-500">No Role</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">{{ $user->role_display }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-gray-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-3">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                           class="text-green-600 hover:text-green-800 text-xs font-medium">Manage</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
