<x-app-layout>
    <x-slot name="header">Reports & Statistics</x-slot>

    <div class="mt-2 space-y-6">
        {{-- Summary stats --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total</p>
                <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Pending</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Approved</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Rejected</p>
                <p class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
            </div>
        </div>

        {{-- Pending by level --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Pending Transfers by Approval Level</h3>
            <div class="grid grid-cols-4 gap-4">
                @foreach(['facility' => 'Facility', 'district' => 'District', 'region' => 'Region', 'ministry' => 'Ministry'] as $key => $label)
                    <div class="text-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-2xl font-bold text-green-600">{{ $byLevel[$key] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Full transfer list --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">All Transfer Records</h2>
                <span class="text-xs text-gray-400">{{ $transfers->count() }} total</span>
            </div>
            @if($transfers->isEmpty())
                <p class="px-6 py-10 text-center text-sm text-gray-400">No records available.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Applicant</th>
                                <th class="px-4 py-3 text-left">From</th>
                                <th class="px-4 py-3 text-left">To</th>
                                <th class="px-4 py-3 text-left">Current Level</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Date</th>
                                <th class="px-4 py-3 text-left"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($transfers as $t)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-400">{{ $t->id }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $t->user->name }}</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ optional($t->fromFacility)->name ?? '—' }}
                                        <div class="text-xs text-gray-400">{{ optional(optional($t->fromFacility)->location)->region }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ optional($t->toFacility)->name ?? '—' }}
                                        <div class="text-xs text-gray-400">{{ optional(optional($t->toFacility)->location)->region }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 capitalize">{{ $t->level }}</td>
                                    <td class="px-4 py-3">
                                        @php $c = $t->status_color @endphp
                                        <span class="px-2 py-0.5 rounded-full text-xs
                                            {{ $c === 'green' ? 'bg-green-100 text-green-700' : ($c === 'red' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ $t->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $t->created_at->format('d M Y') }}</td>
                                    <td class="px-4 py-3">
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
