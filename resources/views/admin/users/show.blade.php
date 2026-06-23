<x-app-layout>
    <x-slot name="header">User: {{ $user->name }}</x-slot>

    <div class="mt-2 max-w-2xl space-y-5">

        {{-- Profile card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <dt class="text-gray-500">Current Role</dt>
                    <dd class="font-medium text-gray-800">{{ $user->role_display }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Registered</dt>
                    <dd class="text-gray-700">{{ $user->created_at->format('d M Y') }}</dd>
                </div>
                @if($user->employeeRecord)
                <div>
                    <dt class="text-gray-500">Title</dt>
                    <dd class="text-gray-700">{{ $user->employeeRecord->title }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Facility</dt>
                    <dd class="text-gray-700">{{ optional($user->employeeRecord->facility)->name ?? '—' }}</dd>
                </div>
                @elseif($user->officialRecord)
                <div>
                    <dt class="text-gray-500">Title</dt>
                    <dd class="text-gray-700">{{ $user->officialRecord->title }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Facility / Scope</dt>
                    <dd class="text-gray-700">
                        {{ optional($user->officialRecord->facility)->name ?? '' }}
                        @if($user->officialRecord->district) District: {{ $user->officialRecord->district }} @endif
                        @if($user->officialRecord->region) Region: {{ $user->officialRecord->region }} @endif
                    </dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Assign Role --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Assign Role</h3>
            <form method="POST" action="{{ route('admin.users.assign-role', $user) }}" class="flex items-end gap-3">
                @csrf
                <div class="flex-1">
                    <label for="role" class="block text-xs font-medium text-gray-600 mb-1">Role</label>
                    <select id="role" name="role"
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Save Role
                </button>
            </form>
        </div>

        {{-- Transfer history --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Transfer History</h3>
            </div>
            @if($user->transfers->isEmpty())
                <p class="px-6 py-6 text-center text-sm text-gray-400">No transfers submitted.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">From</th>
                                <th class="px-6 py-3 text-left">To</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Date</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($user->transfers as $t)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3">{{ $t->from_facility_id }}</td>
                                    <td class="px-6 py-3">{{ $t->to_facility_id }}</td>
                                    <td class="px-6 py-3">
                                        @php $c = $t->status_color @endphp
                                        <span class="px-2 py-0.5 rounded-full text-xs
                                            {{ $c === 'green' ? 'bg-green-100 text-green-700' : ($c === 'red' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ $t->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-gray-400 text-xs">{{ $t->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-3">
                                        <a href="{{ route('transfers.show', $t) }}" class="text-green-600 hover:text-green-800 text-xs">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <a href="{{ route('admin.users') }}" class="text-sm text-green-600 hover:text-green-800">← Back to Users</a>
    </div>
</x-app-layout>
