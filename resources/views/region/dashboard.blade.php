<x-app-layout>
    <x-slot name="header">Regional Health Officer Dashboard</x-slot>

    <div class="mt-2 space-y-6">
        <div class="bg-purple-50 border border-purple-200 rounded-xl px-5 py-4">
            <p class="text-sm text-purple-700">
                Region: <strong>{{ $region }}</strong> &mdash;
                <strong>{{ $pendingCount }}</strong> transfer(s) awaiting regional review.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-700">Pending Regional Reviews</h2>
                <a href="{{ route('transfers.index') }}" class="text-sm text-green-600 hover:text-green-800">All Transfers</a>
            </div>
            @if($pending->isEmpty())
                <p class="px-6 py-10 text-center text-sm text-gray-400">No transfers awaiting regional review.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left">Applicant</th>
                                <th class="px-6 py-3 text-left">From</th>
                                <th class="px-6 py-3 text-left">To</th>
                                <th class="px-6 py-3 text-left">District Comment</th>
                                <th class="px-6 py-3 text-left">Date</th>
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
                                    <td class="px-6 py-3 text-gray-700">{{ optional($t->fromFacility)->name ?? '—' }}</td>
                                    <td class="px-6 py-3 text-gray-700">{{ optional($t->toFacility)->name ?? '—' }}</td>
                                    <td class="px-6 py-3 text-gray-500 text-xs italic max-w-xs truncate">{{ $t->district_comment ?? '—' }}</td>
                                    <td class="px-6 py-3 text-gray-400 text-xs">{{ $t->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-3">
                                        <a href="{{ route('transfers.review', $t) }}"
                                           class="bg-green-600 hover:bg-green-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition">
                                            Review
                                        </a>
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
