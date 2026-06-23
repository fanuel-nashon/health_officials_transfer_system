<x-app-layout>
    <x-slot name="header">Transfer #{{ $transfer->id }}</x-slot>

    <div class="mt-2 max-w-3xl space-y-5">

        {{-- Status banner --}}
        @php $color = $transfer->status_color @endphp
        <div class="rounded-xl p-4 border
            {{ $color === 'green' ? 'bg-green-50 border-green-200' : ($color === 'red' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200') }}">
            <div class="flex items-center gap-3">
                @if($color === 'green')
                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                @elseif($color === 'red')
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                @else
                    <svg class="w-5 h-5 text-yellow-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                @endif
                <div>
                    <p class="text-sm font-semibold
                        {{ $color === 'green' ? 'text-green-700' : ($color === 'red' ? 'text-red-700' : 'text-yellow-700') }}">
                        {{ $transfer->status_label }}
                    </p>
                    <p class="text-xs {{ $color === 'green' ? 'text-green-600' : ($color === 'red' ? 'text-red-600' : 'text-yellow-600') }}">
                        Submitted on {{ $transfer->created_at->format('d M Y, H:i') }}
                    </p>
                </div>

                @if($transfer->isActive())
                    @role('facility_admin|district_officer|region_officer|ministry_official|admin')
                    <a href="{{ route('transfers.review', $transfer) }}"
                       class="ml-auto bg-green-600 hover:bg-green-700 text-white text-xs font-medium px-4 py-1.5 rounded-lg transition">
                        Review This Transfer
                    </a>
                    @endrole
                @endif
            </div>
        </div>

        {{-- Applicant & Transfer Info --}}
        <div class="grid grid-cols-2 gap-5">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-600 mb-3">Applicant</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Name</dt>
                        <dd class="font-medium text-gray-800">{{ $transfer->user->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Title</dt>
                        <dd class="text-gray-700">{{ optional($transfer->user->employeeRecord)->title ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Email</dt>
                        <dd class="text-gray-700">{{ $transfer->user->email }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-semibold text-gray-600 mb-3">Transfer Details</h3>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-gray-500 mb-0.5">From</dt>
                        <dd class="font-medium text-gray-800">{{ optional($transfer->fromFacility)->name ?? '—' }}</dd>
                        <dd class="text-xs text-gray-400">{{ optional(optional($transfer->fromFacility)->location)->district }}, {{ optional(optional($transfer->fromFacility)->location)->region }}</dd>
                    </div>
                    <div class="pt-1">
                        <dt class="text-gray-500 mb-0.5">To</dt>
                        <dd class="font-medium text-gray-800">{{ optional($transfer->toFacility)->name ?? '—' }}</dd>
                        <dd class="text-xs text-gray-400">{{ optional(optional($transfer->toFacility)->location)->district }}, {{ optional(optional($transfer->toFacility)->location)->region }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Reason --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-600 mb-2">Reason for Transfer</h3>
            <p class="text-sm text-gray-700 leading-relaxed">{{ $transfer->reason }}</p>
        </div>

        {{-- Review Trail --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-600 mb-4">Approval Trail</h3>
            <ol class="relative border-l border-gray-200 space-y-5 ml-3">
                @php
                    $levels = [
                        'facility' => ['label' => 'Facility Administrator', 'comment' => $transfer->facility_comment],
                        'district' => ['label' => 'District Health Officer', 'comment' => $transfer->district_comment],
                        'region'   => ['label' => 'Regional Health Officer', 'comment' => $transfer->region_comment],
                        'ministry' => ['label' => 'Ministry Official', 'comment' => $transfer->ministry_comment],
                    ];
                    $levelOrder = ['facility', 'district', 'region', 'ministry'];
                    $currentIndex = array_search($transfer->level, $levelOrder);
                @endphp

                @foreach($levels as $lvl => $info)
                    @php
                        $lvlIndex = array_search($lvl, $levelOrder);
                        $isDone   = $info['comment'] !== null;
                        $isCurrent= $lvl === $transfer->level && $transfer->level_status === 'pending';
                        $isPast   = $lvlIndex < $currentIndex && !$isCurrent;
                    @endphp
                    <li class="ms-4">
                        <div class="absolute w-3 h-3 rounded-full -start-1.5 border border-white
                            {{ $isDone && $transfer->isRejected() && $lvl === $transfer->level ? 'bg-red-400' : ($isDone ? 'bg-green-400' : ($isCurrent ? 'bg-yellow-400' : 'bg-gray-200')) }}">
                        </div>
                        <p class="text-xs font-semibold text-gray-600">{{ $info['label'] }}</p>
                        @if($isDone)
                            <p class="mt-0.5 text-xs text-gray-500 italic">"{{ $info['comment'] }}"</p>
                        @elseif($isCurrent)
                            <p class="mt-0.5 text-xs text-yellow-600">Pending review…</p>
                        @else
                            <p class="mt-0.5 text-xs text-gray-300">Awaiting</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>

        <a href="{{ route('transfers.index') }}" class="text-sm text-green-600 hover:text-green-800">← Back to Transfers</a>
    </div>
</x-app-layout>
