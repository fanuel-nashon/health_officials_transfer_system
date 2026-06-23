<x-app-layout>
    <x-slot name="header">My Dashboard</x-slot>

    <div class="mt-2 space-y-6">
        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Active Requests</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $activeCount }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Approved</p>
                <p class="text-3xl font-bold text-green-600">{{ $approvedCount }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Rejected</p>
                <p class="text-3xl font-bold text-red-600">{{ $rejectedCount }}</p>
            </div>
        </div>

        {{-- Action card --}}
        @if($activeCount === 0)
        <div class="bg-green-50 border border-green-200 rounded-xl p-5 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-green-700">No active transfer request</p>
                <p class="text-xs text-green-500 mt-0.5">You can submit a new transfer request now.</p>
            </div>
            <a href="{{ route('transfers.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                Submit Request
            </a>
        </div>
        @endif

        {{-- Transfer history --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">My Transfer History</h2>
                <a href="{{ route('transfers.index') }}" class="text-sm text-green-600 hover:text-green-800">View all</a>
            </div>
            @if($transfers->isEmpty())
                <p class="px-6 py-10 text-center text-sm text-gray-400">You have not submitted any transfer requests yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Destination</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Submitted</th>
                                <th class="px-6 py-3 text-left"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($transfers as $t)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-medium text-gray-800">
                                        {{ optional($t->toFacility)->name ?? '—' }}
                                        <div class="text-xs text-gray-400">{{ optional(optional($t->toFacility)->location)->district }}</div>
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
                                        <a href="{{ route('transfers.show', $t) }}" class="text-green-600 hover:text-green-800 text-xs">Track</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
