<x-app-layout>
    <x-slot name="header">Admin Dashboard</x-slot>

    <div class="mt-2 space-y-6">
        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $cards = [
                    ['label' => 'Total Users',       'value' => $totalUsers,      'color' => 'green', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['label' => 'Facilities',        'value' => $totalFacilities, 'color' => 'teal',   'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['label' => 'Total Transfers',   'value' => $totalTransfers,  'color' => 'blue',   'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                    ['label' => 'Pending',           'value' => $pendingCount,    'color' => 'yellow', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
            @endphp
            @foreach($cards as $card)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">{{ $card['label'] }}</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Approved / Rejected mini stats --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-4">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-green-600">Approved Transfers</p>
                    <p class="text-2xl font-bold text-green-700">{{ $approvedCount }}</p>
                </div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-4">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-red-600">Rejected Transfers</p>
                    <p class="text-2xl font-bold text-red-700">{{ $rejectedCount }}</p>
                </div>
            </div>
        </div>

        {{-- Recent transfers --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">Recent Transfers</h2>
                <a href="{{ route('transfers.index') }}" class="text-sm text-green-600 hover:text-green-800">View all</a>
            </div>
            @if($recentTransfers->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-gray-400">No transfers yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Applicant</th>
                                <th class="px-6 py-3 text-left">From → To</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Date</th>
                                <th class="px-6 py-3 text-left"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($recentTransfers as $t)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-medium text-gray-800">{{ $t->user->name }}</td>
                                    <td class="px-6 py-3 text-gray-600">
                                        {{ optional($t->fromFacility)->name ?? '—' }} → {{ optional($t->toFacility)->name ?? '—' }}
                                    </td>
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

        {{-- Quick Links --}}
        <div class="grid grid-cols-3 gap-4">
            <a href="{{ route('admin.users') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-green-300 transition group">
                <div class="w-10 h-10 bg-green-50 group-hover:bg-green-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700">Manage Users</p>
                <p class="text-xs text-gray-400 mt-0.5">Assign roles to system users</p>
            </a>
            <a href="{{ route('facilities.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-green-300 transition group">
                <div class="w-10 h-10 bg-teal-50 group-hover:bg-teal-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700">Manage Facilities</p>
                <p class="text-xs text-gray-400 mt-0.5">Add and manage health facilities</p>
            </a>
            <a href="{{ route('locations.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-green-300 transition group">
                <div class="w-10 h-10 bg-blue-50 group-hover:bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700">Manage Locations</p>
                <p class="text-xs text-gray-400 mt-0.5">Manage districts and regions</p>
            </a>
        </div>
    </div>
</x-app-layout>
