<x-app-layout>
    <x-slot name="header">Facility Administrator Dashboard</x-slot>

    <div class="mt-2 space-y-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-5 py-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-yellow-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm text-yellow-700">
                You have <strong>{{ $pendingCount }}</strong> transfer request(s) pending your review.
            </p>
        </div>

        {{-- Pending reviews --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Pending Review</h2>
            </div>
            @if($pending->isEmpty())
                <p class="px-6 py-10 text-center text-sm text-gray-400">No transfers awaiting your review.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Applicant</th>
                                <th class="px-6 py-3 text-left">Requested Destination</th>
                                <th class="px-6 py-3 text-left">Submitted</th>
                                <th class="px-6 py-3 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($pending as $t)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        <div class="font-medium text-gray-800">{{ $t->user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ optional($t->user->employeeRecord)->title }}</div>
                                    </td>
                                    <td class="px-6 py-3 text-gray-700">{{ optional($t->toFacility)->name ?? '—' }}</td>
                                    <td class="px-6 py-3 text-gray-400 text-xs">{{ $t->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-3 flex gap-3">
                                        <a href="{{ route('transfers.review', $t) }}"
                                           class="bg-green-600 hover:bg-green-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition">
                                            Review
                                        </a>
                                        <a href="{{ route('transfers.show', $t) }}"
                                           class="text-gray-500 hover:text-gray-700 text-xs">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Recent transfers --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">All Facility Transfers</h2>
                <a href="{{ route('transfers.index') }}" class="text-sm text-green-600 hover:text-green-800">View all</a>
            </div>
            @if($recentTransfers->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-gray-400">No transfers found for your facility.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Applicant</th>
                                <th class="px-6 py-3 text-left">To</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Date</th>
                                <th class="px-6 py-3 text-left"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($recentTransfers as $t)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-medium text-gray-800">{{ $t->user->name }}</td>
                                    <td class="px-6 py-3 text-gray-600">{{ optional($t->toFacility)->name ?? '—' }}</td>
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
    </div>
</x-app-layout>
