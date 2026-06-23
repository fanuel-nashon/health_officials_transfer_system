<x-app-layout>
    <x-slot name="header">Transfers</x-slot>

    <div class="mt-2">
        @role('nurse_doctor')
        <div class="mb-4 flex justify-end">
            <a href="{{ route('transfers.create') }}"
               class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Transfer Request
            </a>
        </div>
        @endrole

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-700">Transfer Requests</h2>
            </div>

            @if($transfers->isEmpty())
                <div class="px-6 py-12 text-center text-gray-400">
                    <svg class="mx-auto w-10 h-10 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm">No transfer requests found.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">#</th>
                                <th class="px-6 py-3 text-left">Applicant</th>
                                <th class="px-6 py-3 text-left">From</th>
                                <th class="px-6 py-3 text-left">To</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Date</th>
                                <th class="px-6 py-3 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                            @foreach($transfers as $transfer)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-gray-400">{{ $transfer->id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium">{{ $transfer->user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ optional($transfer->user->employeeRecord)->title }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>{{ optional($transfer->fromFacility)->name ?? '—' }}</div>
                                        <div class="text-xs text-gray-400">{{ optional(optional($transfer->fromFacility)->location)->district }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>{{ optional($transfer->toFacility)->name ?? '—' }}</div>
                                        <div class="text-xs text-gray-400">{{ optional(optional($transfer->toFacility)->location)->district }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php $color = $transfer->status_color @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $color === 'green' ? 'bg-green-100 text-green-700' : ($color === 'red' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ $transfer->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-400 text-xs">{{ $transfer->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('transfers.show', $transfer) }}"
                                           class="text-green-600 hover:text-green-800 text-xs font-medium">View</a>

                                        @if($transfer->isActive())
                                            @role('facility_admin|district_officer|region_officer|ministry_official|admin')
                                            <a href="{{ route('transfers.review', $transfer) }}"
                                               class="ml-3 text-green-600 hover:text-green-800 text-xs font-medium">Review</a>
                                            @endrole
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $transfers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
